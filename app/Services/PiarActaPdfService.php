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

        $fullPath = Storage::disk('public')->path($report->annex_one);

        return is_file($fullPath) ? $fullPath : null;
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
                'firmas' => $this->buildFirmasData($director, $docentes, $piar->id, $p),
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

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\Users_teacher>  $docentes
     * @return array<int, array{name: string, role: string, image: ?string}>
     */
    private function buildFirmasData($director, $docentes, int $piarId, int $period): array
    {
        $firmas = [];

        if ($director) {
            $firmas[] = [
                'name' => trim($director->name.' '.$director->last_name),
                'role' => 'Dir. Grupo',
                'image' => $this->resolveSignatureDataUri($director, $piarId, $period),
            ];
        }

        foreach ($docentes as $docente) {
            if ($director && $docente->id === $director->id) {
                continue;
            }

            $firmas[] = [
                'name' => trim($docente->name.' '.$docente->last_name),
                'role' => 'Docente',
                'image' => $this->resolveSignatureDataUri($docente, $piarId, $period),
            ];
        }

        return $firmas;
    }

    private function resolveSignatureDataUri(Users_teacher $teacher, int $piarId, int $period): ?string
    {
        $sig = PiarAdjustment::where('piar_id', $piarId)
            ->where('period', $period)
            ->where('teacher_id', $teacher->id)
            ->whereNotNull('teacher_signature')
            ->value('teacher_signature')
            ?? $teacher->signature;

        $filePath = $this->resolveSignatureFilePath($sig);

        return $filePath ? PdfImageHelper::dataUriForDompdf($filePath) : null;
    }

    private function resolveSignatureFilePath(?string $sig): ?string
    {
        if (! $sig) {
            return null;
        }

        $sig = str_replace('\\', '/', ltrim($sig, '/'));

        $candidates = [];

        if (str_contains($sig, 'Imagenes_Firma')) {
            $candidates[] = public_path($sig);
        } else {
            $candidates[] = Storage::disk('public')->path($sig);
            $candidates[] = storage_path('app/public/'.$sig);
            $candidates[] = public_path($sig);
        }

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
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
