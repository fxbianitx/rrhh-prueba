<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeSearchRequest;
use App\Services\Employee\EmployeeSearchService;
use Illuminate\Http\JsonResponse;

class EmployeeSearchController extends Controller
{
    //! Buscar empleados
    public function __invoke(EmployeeSearchRequest $request, EmployeeSearchService $searchService): JsonResponse
    {
        // Validar campoos
        $filters = $request->validated();

        // Buscar empleados por nombre o dni
        $employees = $searchService->handle($filters)->appends($filters);

        // Decidir el orden
        $currentSortBy = $filters['sort_by'] ?? 'full_name';

        // Decidir direccion
        $currentSortDirection = $filters['sort_direction'] ?? 'asc';

        // Preparar tabla filtrada
        $html = view('employees.partials.table', compact('employees', 'currentSortBy', 'currentSortDirection'))->render();

        return response()->json([
            'html' => $html,
        ]);
    }
}
