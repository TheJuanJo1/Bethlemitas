<?php

namespace App\Services;

use App\Models\Piar;
use App\Models\PiarAdjustment;
use App\Models\PiarFamilyActivity;
use App\Models\Psychoorientation;
use App\Models\Users_teacher;
use App\Support\PdfImageHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Symfony\Component\HttpFoundation\Response;

class PiarActaPdfService
{
    /**
     * @param  int|string  $periodo  1, 2, 3 o "todos"
     */
    public function download(int $piarId, int|string $periodo): Response
    {
        $periodo = $this->normalizePeriodo($periodo);
        $piar = $this->loadPiar($piarId);

        if (! $piar->characteristics) {
            abort(403, 'El acta no está disponible hasta que el Psicoorientador diligencie la caracterización.');
        }

        $periodosAnexo = $periodo === 'todos' ? [1, 2, 3] : [$periodo];
        $merger = new Fpdi();
        $merger->SetAutoPageBreak(true, 10);

        $anexo1Path = $this->resolveAnexo1Path($piar);
        if ($anexo1Path) {
            $this->appendPdfFile($merger, $anexo1Path);
        }

        $anexo2Pdf = $this->renderAnexo2Pdf($piar, $periodosAnexo);
        $this->appendPdfBinary($merger, $anexo2Pdf);

        $anexo3Pdf = $this->renderAnexo3Pdf($piar, $periodosAnexo);
        $this->appendPdfBinary($merger, $anexo3Pdf);

        $suffix = $periodo === 'todos' ? 'todos_periodos' : 'periodo_'.$periodo;
        $filename = 'ACTA_'.$piar->student->name.'_'.$suffix.'.pdf';

        return response($merger->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function normalizePeriodo(int|string $periodo): int|string
    {
        if ($periodo === 'todos' || $periodo === 'all') {
            return 'todos';
        }

        $periodo = (int) $periodo;
        if (! in_array($periodo, [1, 2, 3], true)) {
            abort(404, 'Periodo no válido.');
        }

        return $periodo;
    }

    private function loadPiar(int $piarId): Piar
    {
        return Piar::with([
            'student.degree',
            'student.group.director',
            'teacher',
            'characteristics',
        ])->findOrFail($piarId);
    }

    private function resolveAnexo1Path(Piar $piar): ?string
    {
        $report = Psychoorientation::where('id_user_student', $piar->student_id)
            ->whereNotNull('annex_one')
            ->latest()
            ->first();

        if (! $report?->annex_one) {
            return null;
        }

        $relative = ltrim(str_replace('\\', '/', $report->annex_one), '/');

        $candidates = [
            Storage::disk('public')->path($relative),
            storage_path('app/public/'.$relative),
        ];

        foreach ($candidates as $fullPath) {
            if (is_file($fullPath)) {
                return $fullPath;
            }
        }

        return null;
    }

  /**
     * @param  int[]  $periodos
     */
    private function renderAnexo2Pdf(Piar $piar, array $periodos): string
    {
        $periodoData = [];
        foreach ([1, 2, 3] as $p) {
            $periodoData[$p] = PiarAdjustment::with('teacher')
                ->where('piar_id', $piar->id)
                ->where('period', $p)
                ->get();
        }

        $periodosLista = [];
        foreach ($periodos as $p) {
            $periodosLista[] = [
                'id' => $p,
                'nombre' => $p.' Periodo',
                'data' => $periodoData[$p],
            ];
        }

        return Pdf::loadView('pdf.piar_anexo2_acta', [
            'piar' => $piar,
            'periodosLista' => $periodosLista,
            'mostrarCaracterizacion' => true,
            'docentesElaboran' => PiarFirmasResolver::docentesConAreas($piar->id, $periodos),
            'firmasDocentesAnexo2' => PiarFirmasResolver::firmasDocentesAnexo2($piar->id, $periodos, true),
        ])->output();
    }

    /**
     * @param  int[]  $periodos
     */
    private function renderAnexo3Pdf(Piar $piar, array $periodos): string
    {
        $estudiante = $piar->student;
        $director = $estudiante->group->director ?? null;
        $bloques = [];

        foreach ($periodos as $p) {
            $adjustments = PiarAdjustment::where('piar_id', $piar->id)
                ->where('period', $p)
                ->get();

            $docentesIds = $adjustments->pluck('teacher_id')->unique()->filter();
            $docentes = Users_teacher::whereIn('id', $docentesIds)->get();

            $familyActivities = PiarFamilyActivity::where('piar_id', $piar->id)
                ->where('period', $p)
                ->get();

            $bloques[] = [
                'periodo' => $p,
                'docentes' => $docentes,
                'familyActivities' => $familyActivities,
                'firmasAnexo3' => PiarFirmasResolver::firmasAnexo3(
                    $estudiante,
                    $director,
                    $piar->id,
                    $p,
                    $estudiante->acudiente,
                    true
                ),
            ];
        }

        return Pdf::loadView('pdf.acta_anexo3', [
            'piar' => $piar,
            'estudiante' => $estudiante,
            'director' => $director,
            'bloques' => $bloques,
            'familiar_manual' => $estudiante->acudiente,
            'parentesco_manual' => $estudiante->parentesco_acudiente,
            'logoPath' => PdfImageHelper::institutionalLogoDataUri(),
        ])->output();
    }

    private function appendPdfFile(Fpdi $merger, string $filePath): void
    {
        $pageCount = $merger->setSourceFile($filePath);

        for ($page = 1; $page <= $pageCount; $page++) {
            $template = $merger->importPage($page);
            $size = $merger->getTemplateSize($template);
            $merger->AddPage($size['orientation'] ?? 'P', [$size['width'], $size['height']]);
            $merger->useTemplate($template);
        }
    }

    private function appendPdfBinary(Fpdi $merger, string $binary): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'acta_');
        file_put_contents($tmp, $binary);

        try {
            $this->appendPdfFile($merger, $tmp);
        } finally {
            @unlink($tmp);
        }
    }
}
