@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Resumen de empleados activos</h1>
            <p class="text-muted mb-0">Consultar empleados activos por fecha de corte.</p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong>Consulta por fecha</strong>
        </div>
        <div class="card-body">
            <form id="employee-summary-form" data-url="{{ route('employees.summary') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="cutoff_date" class="form-label">Fecha de corte</label>
                        <input type="date" name="cutoff_date" id="cutoff_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Consultar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Resultado</strong>
            <span id="summary-total" class="badge text-bg-primary d-none"></span>
        </div>
        <div class="card-body">
            <div id="employee-summary-feedback" class="alert alert-danger d-none"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Area</th>
                            <th>Total empleados activos</th>
                        </tr>
                    </thead>
                    <tbody id="summary-table-body">
                        <tr>
                            <td colspan="2" class="text-center text-muted">Aun no se ha realizado una consulta.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/summary/index.js') }}"></script>
@endpush
