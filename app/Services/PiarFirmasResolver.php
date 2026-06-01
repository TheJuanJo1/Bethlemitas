<?php

namespace App\Services;

use App\Models\PiarAdjustment;
use App\Models\Psychoorientation;
use App\Models\Users_student;
use App\Models\Users_teacher;
use App\Support\PdfImageHelper;
use Illuminate\Support\Facades\Storage;

class PiarFirmasResolver
{
    /**
     * Docentes únicos con las áreas en las que intervienen (para Anexo 2).
     *
     * @param  int[]  $periodos
     * @return array<int, array{teacher: Users_teacher, areas: string[]}>
     */
    public static function docentesConAreas(int $piarId, array $periodos): array
    {
        $adjustments = PiarAdjustment::with('teacher')
            ->where('piar_id', $piarId)
            ->whereIn('period', $periodos)
            ->whereNotNull('teacher_id')
            ->get();

        $agrupados = [];

        foreach ($adjustments as $row) {
            if (! $row->teacher) {
                continue;
            }

            $id = $row->teacher_id;

            if (! isset($agrupados[$id])) {
                $agrupados[$id] = [
                    'teacher' => $row->teacher,
                    'areas' => [],
                ];
            }

            $area = trim((string) ($row->area ?? ''));
            $area = $area !== '' ? $area : 'Área no especificada';

            if (! in_array($area, $agrupados[$id]['areas'], true)) {
                $agrupados[$id]['areas'][] = $area;
            }
        }

        usort($agrupados, fn ($a, $b) => strcmp(
            $a['teacher']->last_name.$a['teacher']->name,
            $b['teacher']->last_name.$b['teacher']->name
        ));

        return array_values($agrupados);
    }

    public static function psicorientadoraParaEstudiante(int $studentId): ?Users_teacher
    {
        $informe = Psychoorientation::where('id_user_student', $studentId)
            ->whereNotNull('psychologist_writes')
            ->orderByDesc('report_year')
            ->orderByDesc('created_at')
            ->first();

        if (! $informe?->psychologist_writes) {
            return null;
        }

        return Users_teacher::find($informe->psychologist_writes);
    }

    public static function coordinadorAcademico(): ?Users_teacher
    {
        return Users_teacher::role('coordinador')->orderBy('id')->first();
    }

    /**
     * Firmas del Anexo 3: 2 manuales + director, psicoorientadora y coordinador.
     *
     * @return array{manual: array<int, array{label: string, name: ?string}>, digital: array<int, array{label: string, name: string, image: ?string}>}
     */
    public static function firmasAnexo3(
        Users_student $estudiante,
        ?Users_teacher $director,
        int $piarId,
        int $period,
        ?string $familiarManual = null,
        bool $paraPdf = false
    ): array {
        $manual = [
            [
                'label' => 'Firma del Acudiente',
                'name' => $familiarManual ?? $estudiante->acudiente,
            ],
            [
                'label' => 'Firma del Estudiante',
                'name' => trim($estudiante->name.' '.$estudiante->last_name),
            ],
        ];

        $digital = [];

        if ($director) {
            $digital[] = [
                'label' => 'Director de Grupo',
                'name' => trim($director->name.' '.$director->last_name),
                'image' => self::imagenFirma($director, $piarId, $period, $paraPdf),
            ];
        }

        $psico = self::psicorientadoraParaEstudiante($estudiante->id);
        if ($psico) {
            $digital[] = [
                'label' => 'Docente Orientadora',
                'name' => trim($psico->name.' '.$psico->last_name),
                'image' => self::imagenFirma($psico, null, null, $paraPdf),
            ];
        }

        $coordinador = self::coordinadorAcademico();
        if ($coordinador) {
            $digital[] = [
                'label' => 'Coordinador Académico',
                'name' => trim($coordinador->name.' '.$coordinador->last_name),
                'image' => self::imagenFirma($coordinador, null, null, $paraPdf),
            ];
        }

        return [
            'manual' => $manual,
            'digital' => $digital,
        ];
    }

    public static function imagenFirma(
        Users_teacher $teacher,
        ?int $piarId,
        ?int $period,
        bool $paraPdf
    ): ?string {
        $archivo = self::archivoFirma($teacher, $piarId, $period);

        if (! $archivo) {
            return null;
        }

        if ($paraPdf) {
            return PdfImageHelper::dataUriForDompdf($archivo);
        }

        $sig = self::rutaFirmaEnBd($teacher, $piarId, $period);

        if (! $sig) {
            return null;
        }

        return str_contains($sig, 'Imagenes_Firma')
            ? asset($sig)
            : asset('storage/'.$sig);
    }

    private static function archivoFirma(Users_teacher $teacher, ?int $piarId, ?int $period): ?string
    {
        $sig = self::rutaFirmaEnBd($teacher, $piarId, $period);

        if (! $sig) {
            return null;
        }

        $sig = str_replace('\\', '/', ltrim($sig, '/'));

        $candidatos = str_contains($sig, 'Imagenes_Firma')
            ? [public_path($sig)]
            : [
                Storage::disk('public')->path($sig),
                storage_path('app/public/'.$sig),
                public_path($sig),
            ];

        foreach ($candidatos as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    private static function rutaFirmaEnBd(Users_teacher $teacher, ?int $piarId, ?int $period): ?string
    {
        if ($piarId && $period) {
            $desdeAjuste = PiarAdjustment::where('piar_id', $piarId)
                ->where('period', $period)
                ->where('teacher_id', $teacher->id)
                ->whereNotNull('teacher_signature')
                ->value('teacher_signature');

            if ($desdeAjuste) {
                return $desdeAjuste;
            }
        }

        return $teacher->signature;
    }
}
