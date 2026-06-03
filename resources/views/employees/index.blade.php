@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Empleados</h1>
            <p class="text-muted mb-0">Gestionar listado y filtros de empleados.</p>
        </div>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">Nuevo empleado</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong>Filtros de busqueda</strong>
        </div>
        <div class="card-body">
            <form id="employee-search-form" data-url="{{ route('employees.search') }}">
                <input type="hidden" name="sort_by" id="sort_by" value="full_name">
                <input type="hidden" name="sort_direction" id="sort_direction" value="asc">

                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label for="search" class="form-label">Busqueda general</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                id="search"
                                class="form-control"
                                placeholder="Busqueda por nombre o DNI"
                            >
                        </div>
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-lg">
                        <label for="area_id" class="form-label">Area</label>
                        <select name="area_id" id="area_id" class="form-select">
                            <option value="">Todas</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg">
                        <label for="position_id" class="form-label">Cargo</label>
                        <select name="position_id" id="position_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg">
                        <label for="location_id" class="form-label">Local</label>
                        <select name="location_id" id="location_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg">
                        <label for="start_date_from" class="form-label">Fecha inicio desde</label>
                        <input type="date" name="start_date_from" id="start_date_from" class="form-control">
                    </div>

                    <div class="col-lg">
                        <label for="start_date_to" class="form-label">Fecha inicio hasta</label>
                        <input type="date" name="start_date_to" id="start_date_to" class="form-control">
                    </div>

                    <div class="col-lg-auto">
                        <button type="button" id="employee-search-reset" class="btn btn-outline-secondary">Limpiar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <strong>Listado de empleados</strong>
        </div>
        <div class="card-body">
            <div id="employee-search-feedback" class="alert alert-danger d-none"></div>
            <div id="employees-table-wrapper">
                @include('employees.partials.table', [
                    'employees' => $employees,
                    'currentSortBy' => $currentSortBy,
                    'currentSortDirection' => $currentSortDirection,
                ])
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/employees/index.js') }}"></script>
@endpush
