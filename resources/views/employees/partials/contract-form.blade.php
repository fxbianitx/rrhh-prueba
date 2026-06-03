@php
    $selectedContractType = old('contract_type');
    $isIndefiniteContract = $selectedContractType === \App\Enums\ContractType::INDEFINIDO->value;
@endphp

<div class="row g-3" data-contract-form data-indefinite-contract="{{ \App\Enums\ContractType::INDEFINIDO->value }}">
    @if (!empty($employeeId))
        <input type="hidden" name="employee_id" value="{{ $employeeId }}">
    @endif

    <div class="col-md-4">
        <label for="contract_type" class="form-label">Tipo de contrato <span class="text-danger">*</span></label>
        <select
            name="contract_type"
            id="contract_type"
            class="form-select @error('contract_type') is-invalid @enderror"
            required
            data-contract-type
        >
            <option value="">Seleccionar</option>
            @foreach (\App\Enums\ContractType::cases() as $contractType)
                <option value="{{ $contractType->value }}" @selected(old('contract_type') === $contractType->value)>
                    {{ $contractType->value }}
                </option>
            @endforeach
        </select>
        @error('contract_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="start_date" class="form-label">Fecha de inicio <span class="text-danger">*</span></label>
        <input
            type="date"
            name="start_date"
            id="start_date"
            class="form-control @error('start_date') is-invalid @enderror"
            value="{{ old('start_date') }}"
            required
            data-contract-start-date
        >
        @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="end_date" class="form-label">
            Fecha de fin
            <span class="text-danger {{ $isIndefiniteContract ? 'd-none' : '' }}" data-contract-end-required>*</span>
        </label>
        <input
            type="date"
            name="end_date"
            id="end_date"
            class="form-control @error('end_date') is-invalid @enderror"
            value="{{ old('end_date') }}"
            data-contract-end-date
            {{ $isIndefiniteContract ? '' : 'required' }}
            @if(old('start_date')) min="{{ old('start_date') }}" @endif
        >
        <div class="form-text">Obligatoria salvo contrato indefinido.</div>
        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
