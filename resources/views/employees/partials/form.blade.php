@if (($section ?? 'personal') === 'personal')
    <div class="row g-3">
        <div class="col-md-6">
            <label for="first_name" class="form-label">Nombres <span class="text-danger">*</span></label>
            <input
                type="text"
                name="first_name"
                id="first_name"
                class="form-control @error('first_name') is-invalid @enderror"
                value="{{ old('first_name', $employee->first_name ?? '') }}"
                required
            >
            @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="last_name" class="form-label">Apellidos <span class="text-danger">*</span></label>
            <input
                type="text"
                name="last_name"
                id="last_name"
                class="form-control @error('last_name') is-invalid @enderror"
                value="{{ old('last_name', $employee->last_name ?? '') }}"
                required
            >
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="dni" class="form-label">DNI <span class="text-danger">*</span></label>
            <input
                type="text"
                name="dni"
                id="dni"
                class="form-control @error('dni') is-invalid @enderror"
                value="{{ old('dni', $employee->dni ?? '') }}"
                required
            >
            @error('dni')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="email" class="form-label">Correo <span class="text-danger">*</span></label>
            <input
                type="email"
                name="email"
                id="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $employee->email ?? '') }}"
                required
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="birth_date" class="form-label">Fecha de nacimiento <span class="text-danger">*</span></label>
            <input
                type="date"
                name="birth_date"
                id="birth_date"
                class="form-control @error('birth_date') is-invalid @enderror"
                value="{{ old('birth_date', $employee->birth_date ?? '') }}"
                required
            >
            @error('birth_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@else
    <div class="row g-3">
        <div class="col-md-4">
            <label for="area_id" class="form-label">Area <span class="text-danger">*</span></label>
            <select name="area_id" id="area_id" class="form-select @error('area_id') is-invalid @enderror" required>
                <option value="">Seleccionar</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected(old('area_id', $employee->area_id ?? '') == $area->id)>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
            @error('area_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="position_id" class="form-label">Cargo <span class="text-danger">*</span></label>
            <select name="position_id" id="position_id" class="form-select @error('position_id') is-invalid @enderror" required>
                <option value="">Seleccionar</option>
                @foreach ($positions as $position)
                    <option value="{{ $position->id }}" @selected(old('position_id', $employee->position_id ?? '') == $position->id)>
                        {{ $position->name }}
                    </option>
                @endforeach
            </select>
            @error('position_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="location_id" class="form-label">Local <span class="text-danger">*</span></label>
            <select name="location_id" id="location_id" class="form-select @error('location_id') is-invalid @enderror" required>
                <option value="">Seleccionar</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" @selected(old('location_id', $employee->location_id ?? '') == $location->id)>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
            @error('location_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endif
