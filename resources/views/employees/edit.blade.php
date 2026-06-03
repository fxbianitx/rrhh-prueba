@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Editar empleado</h1>
            <p class="text-muted mb-0">Actualizar datos personales y laborales.</p>
        </div>
    </div>

    <form action="{{ route('employees.update', $employee) }}" method="POST" id="employee-edit-form">
        @csrf
        @method('PUT')

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Datos personales</strong>
            </div>
            <div class="card-body">
                @include('employees.partials.form', ['employee' => $employee, 'section' => 'personal'])
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Datos laborales</strong>
            </div>
            <div class="card-body">
                @include('employees.partials.form', ['employee' => $employee, 'section' => 'laboral'])
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" id="employee-update-button">Actualizar</button>
            <a href="{{ route('employees.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('employee-edit-form');
            const submitButton = document.getElementById('employee-update-button');

            if (!form || !submitButton) {
                return;
            }

            const requiredFields = form.querySelectorAll('input[required], select[required]');

            const toggleSubmitButton = function () {
                const hasEmptyField = Array.from(requiredFields).some(function (field) {
                    return !field.value.trim();
                });

                submitButton.disabled = hasEmptyField;
            };

            requiredFields.forEach(function (field) {
                field.addEventListener('input', toggleSubmitButton);
                field.addEventListener('change', toggleSubmitButton);
            });

            toggleSubmitButton();
        });
    </script>
@endpush
