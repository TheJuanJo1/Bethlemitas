<?php

namespace App\Http\Controllers;

use App\Models\Piar;
use App\Models\PiarAnnualReport;
use App\Models\Users_student;
use App\Models\Users_teacher;
use App\Services\PiarFirmasResolver;
use App\Support\PdfImageHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiarAnnualReportController extends Controller
{
    /**
     * Mostrar/Editar el informe anual del PIAR.
     */
    public function edit($piar_id)
    {
        $piar = Piar::with(['student.degree', 'student.group', 'teacher', 'annualReport'])->findOrFail($piar_id);
        $student = $piar->student;
        $user = Auth::user();

        // Validar permisos: solo el director del grupo del estudiante puede editar
        if ($user->hasRole('docente')) {
            if ($user->group_director !== $student->id_group || $user->group_director === null) {
                abort(403, 'No tienes permiso para diligenciar el informe anual de este estudiante, ya que no eres su director de grupo.');
            }
        }

        $annualReport = $piar->annualReport ?? new PiarAnnualReport();
        $director = Users_teacher::where('group_director', $student->id_group)->first();

        return view('piar.annual_report.form', compact('piar', 'student', 'annualReport', 'director'));
    }

    /**
     * Guardar/Actualizar el informe anual del PIAR.
     */
    public function store(Request $request, $piar_id)
    {
        $piar = Piar::findOrFail($piar_id);
        $student = $piar->student;
        $user = Auth::user();

        // Validar permisos
        if ($user->hasRole('docente')) {
            if ($user->group_director !== $student->id_group || $user->group_director === null) {
                abort(403, 'No tienes permiso para guardar el informe anual de este estudiante.');
            }
        }

        $request->validate([
            'competencies' => 'nullable|string',
            'aspects' => 'nullable|string',
            'behavior_observation' => 'nullable|string',
            'academic_observation' => 'nullable|string',
            'recommendations' => 'nullable|string',
        ]);

        $piar->annualReport()->updateOrCreate(
            ['piar_id' => $piar->id],
            [
                'competencies' => $request->competencies,
                'aspects' => $request->aspects,
                'behavior_observation' => $request->behavior_observation,
                'academic_observation' => $request->academic_observation,
                'recommendations' => $request->recommendations,
            ]
        );

        return redirect()->back()->with('success', 'Informe anual guardado correctamente.');
    }

    /**
     * Generar y descargar/visualizar el informe anual en PDF.
     */
    public function pdf($piar_id)
    {
        $piar = Piar::with(['student.degree', 'student.group', 'teacher', 'annualReport'])->findOrFail($piar_id);
        $student = $piar->student;
        $annualReport = $piar->annualReport ?? new PiarAnnualReport();

        // Obtener director de grupo del estudiante
        $director = Users_teacher::where('group_director', $student->id_group)->first();
        
        // Resolver firmas
        $directorFirma = $director ? PiarFirmasResolver::imagenFirma($director, null, null, true) : null;
        $psico = PiarFirmasResolver::psicorientadoraParaEstudiante($student->id);
        $psicoFirma = $psico ? PiarFirmasResolver::imagenFirma($psico, null, null, true) : null;
        $coordinador = PiarFirmasResolver::coordinadorAcademico();
        $coordinadorFirma = $coordinador ? PiarFirmasResolver::imagenFirma($coordinador, null, null, true) : null;

        // Cargar imagen de encabezado
        $headerLogo = PdfImageHelper::dataUriForDompdf(public_path('img/ImagenAnual.png'));

        // Generar PDF
        $pdf = Pdf::loadView('piar.annual_report.pdf', compact(
            'piar',
            'student',
            'annualReport',
            'director',
            'directorFirma',
            'psico',
            'psicoFirma',
            'coordinador',
            'coordinadorFirma',
            'headerLogo'
        ));

        return $pdf->stream('Informe_Anual_' . $student->name . '.pdf');
    }
}
