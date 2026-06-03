<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeSummaryRequest;
use App\Services\Summary\EmployeeSummaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeSummaryController extends Controller
{
    //! Obtener resumen de empleados
    public function __invoke(Request $request, EmployeeSummaryService $service): JsonResponse|View
    {
        // Si no hay respuesta Json, Ajax o fecha de corte retornar vista normal
        if (!$request->expectsJson() && !$request->ajax() && !$request->filled('cutoff_date')) {
            return view('summary.index');
        }

        $summaryRequest = new EmployeeSummaryRequest();

        $validated = $request->validate(
            $summaryRequest->rules(),
            $summaryRequest->messages(),
            $summaryRequest->attributes()
        );

        $summary = $service->handle($validated['cutoff_date']);

        return response()->json($summary);
    }
}
