@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Detalle del empleado</h1>
            <p class="text-muted mb-0">Consultar informacion general e historial de contratos.</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn btn-warning">Volver</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong>Datos del empleado</strong>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" value="{{ $employee->first_name }} {{ $employee->last_name }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">DNI</label>
                    <input type="text" class="form-control" value="{{ $employee->dni }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Correo</label>
                    <input type="text" class="form-control" value="{{ $employee->email }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Area</label>
                    <input type="text" class="form-control" value="{{ optional($employee->area)->name }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cargo</label>
                    <input type="text" class="form-control" value="{{ optional($employee->position)->name }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Local</label>
                    <input type="text" class="form-control" value="{{ optional($employee->location)->name }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de nacimiento</label>
                    <input type="text" class="form-control" value="{{ $employee->birth_date }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado actual</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ optional($contracts->first())->contract_status === 'Vigente' ? 'Activo' : 'Inactivo' }}"
                        readonly
                    >
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong>Historial de contratos</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo de contrato</th>
                            <th>Fecha de inicio</th>
                            <th>Fecha de fin</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contracts as $contract)
                            <tr>
                                <td>{{ $contract->contract_type }}</td>
                                <td>{{ $contract->start_date }}</td>
                                <td>{{ $contract->end_date ?? '--' }}</td>
                                <td>{{ $contract->contract_status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay contratos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <strong>Registrar renovacion</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('contracts.renew', $employee) }}" method="POST">
                @csrf
                @include('employees.partials.contract-form', ['employeeId' => $employee->id])

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Registrar renovacion</button>
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">Editar empleado</a>
                </div>
            </form>
        </div>
    </div>
@endsection
