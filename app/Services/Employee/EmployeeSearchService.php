<?php

namespace App\Services\Employee;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;

class EmployeeSearchService
{
    //! Buscar empleados
    public function handle(array $filters): LengthAwarePaginator
    {
        $query = $this->baseQuery();

        $this->applySearch($query, $filters);
        $this->applyAreaFilter($query, $filters);
        $this->applyPositionFilter($query, $filters);
        $this->applyLocationFilter($query, $filters);
        $this->applyContractDateFilter($query, $filters);
        $this->applySorting($query, $filters);

        // Retornar empleados con paginacion
        return $query->paginate(16);
    }

    //! Preparar consulta de empleados
    private function baseQuery(): Builder
    {
        return Employee::query()
            ->with(['area', 'position', 'location', 'contracts'])
            ->select('employees.*');
    }

    //! Aplicar busqueda por empleado
    private function applySearch(Builder $query, array $filters): void
    {
        if (!isset($filters['search'])) {
            return;
        }

        $searchTerm = trim($filters['search']);

        $query->where(function (Builder $employeeQuery) use ($searchTerm) {
            $employeeQuery->where('dni', 'like', '%' . $searchTerm . '%')
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ['%' . $searchTerm . '%'])
                ->orWhereRaw("CONCAT(last_name, ' ', first_name) like ?", ['%' . $searchTerm . '%'])
                ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
        });
    }

    //! Aplicar filtro por area
    private function applyAreaFilter(Builder $query, array $filters): void
    {
        if (!isset($filters['area_id'])) {
            return;
        }

        $query->where('area_id', $filters['area_id']);
    }

    //! Aplicar filtro por cargo
    private function applyPositionFilter(Builder $query, array $filters): void
    {
        if (!isset($filters['position_id'])) {
            return;
        }

        $query->where('position_id', $filters['position_id']);
    }

    //! Aplicar filtro por local
    private function applyLocationFilter(Builder $query, array $filters): void
    {
        if (!isset($filters['location_id'])) {
            return;
        }

        $query->where('location_id', $filters['location_id']);
    }

    //! Aplicar filtro por contratacion
    private function applyContractDateFilter(Builder $query, array $filters): void
    {
        if (!isset($filters['start_date_from']) && !isset($filters['start_date_to'])) {
            return;
        }

        $query->whereHas('contracts', function (Builder $contractQuery) use ($filters) {
            if (isset($filters['start_date_from'])) {
                $contractQuery->whereDate('start_date', '>=', $filters['start_date_from']);
            }

            if (isset($filters['start_date_to'])) {
                $contractQuery->whereDate('start_date', '<=', $filters['start_date_to']);
            }
        });
    }

    //! Aplicar orden de empleados
    private function applySorting(Builder $query, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'full_name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';

        if ($sortBy === 'full_name') {
            $query->orderBy('employees.last_name', $sortDirection)
                ->orderBy('employees.first_name', $sortDirection);

            return;
        }

        $columns = [
            'dni' => 'dni',
            'email' => 'email',
        ];

        $query->orderBy($columns[$sortBy], $sortDirection)
            ->orderBy('last_name')
            ->orderBy('first_name');
    }
}
