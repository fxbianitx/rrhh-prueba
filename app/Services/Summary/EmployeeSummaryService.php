<?php

namespace App\Services\Summary;

use App\Models\Employee;

class EmployeeSummaryService
{
    //! Obtener resumen de empleados activos
    public function handle(string $cutoffDate): array
    {
        $employees = Employee::query()
            ->with('area')
            ->whereHas('contracts', function ($query) use ($cutoffDate) {
                $query->whereDate('start_date', '<=', $cutoffDate)
                    ->where(function ($contractQuery) use ($cutoffDate) {
                        $contractQuery
                            ->whereNull('end_date')
                            ->orWhereDate('end_date', '>=', $cutoffDate);
                    });
            })
            ->get()
            ->unique('id')
            ->values();

        // Agrupar resultados por area
        $areas = $employees
            ->groupBy('area_id')
            ->map(function ($group) {
                $area = $group->first()->area;
                return [
                    'area_id' => $area ? $area->id : null,
                    'area_name' => $area ? $area->name : 'Sin area',
                    'total' => $group->count(),
                ];
            })
            ->values()
            ->all();

        return [
            'cutoff_date' => $cutoffDate,
            'total_employees' => $employees->count(),
            'areas' => $areas,
        ];
    }
}
