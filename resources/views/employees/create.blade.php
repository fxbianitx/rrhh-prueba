@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Registrar empleado</h1>
            <p class="text-muted mb-0">Ingresar datos del empleado y contrato inicial.</p>
        </div>
    </div>

    <form action="{{ route('employees.store') }}" method="POST" id="employee-create-form">
        @csrf

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Datos personales</strong>
            </div>
            <div class="card-body">
                @include('employees.partials.form', ['section' => 'personal'])
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Datos laborales</strong>
            </div>
            <div class="card-body">
                @include('employees.partials.form', ['section' => 'laboral'])
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Contrato inicial</strong>
            </div>
            <div class="card-body">
                @include('employees.partials.contract-form', ['employeeId' => null])
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" id="employee-store-button">Guardar</button>
            <a href="{{ route('employees.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('employee-create-form');
            const submitButton = document.getElementById('employee-store-button');

            if (!form || !submitButton) {
                return;
            }

            const toggleSubmitButton = function () {
                const requiredFields = form.querySelectorAll('input[required], select[required]');
                const hasEmptyField = Array.from(requiredFields).some(function (field) {
                    return !field.value.trim();
                });

                submitButton.disabled = hasEmptyField;
            };

            form.querySelectorAll('input, select').forEach(function (field) {
                field.addEventListener('input', toggleSubmitButton);
                field.addEventListener('change', toggleSubmitButton);
            });

            toggleSubmitButton();
        });
    </script>
@endpush
