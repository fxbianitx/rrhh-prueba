@php
    $currentSortBy = $currentSortBy ?? 'full_name';
    $currentSortDirection = $currentSortDirection ?? 'asc';
@endphp

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>
                    <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-semibold d-flex align-items-center justify-content-between w-100 employee-sort-trigger" data-sort-by="full_name">
                        <span>Nombre completo</span>
                        <i class="bi {{ $currentSortBy === 'full_name' ? ($currentSortDirection === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up' }} ms-1"></i>
                    </button>
                </th>
                <th>
                    <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-semibold d-flex align-items-center justify-content-between w-100 employee-sort-trigger" data-sort-by="dni">
                        <span>DNI</span>
                        <i class="bi {{ $currentSortBy === 'dni' ? ($currentSortDirection === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up' }} ms-1"></i>
                    </button>
                </th>
                <th>
                    <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-semibold d-flex align-items-center justify-content-between w-100 employee-sort-trigger" data-sort-by="email">
                        <span>Email</span>
                        <i class="bi {{ $currentSortBy === 'email' ? ($currentSortDirection === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up' }} ms-1"></i>
                    </button>
                </th>
                <th>
                    Area
                </th>
                <th>
                    Cargo
                </th>
                <th>
                    Local
                </th>
                <th>
                    Estado
                </th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody id="employees-table-body">
            @forelse ($employees as $employee)
                @php
                    $currentContract = $employee->contracts->sortByDesc('start_date')->first();
                    $isActive = $currentContract && (is_null($currentContract->end_date) || $currentContract->end_date >= now()->toDateString());
                @endphp
                <tr>
                    <td>{{ $employee->last_name }}, {{ $employee->first_name }}</td>
                    <td>{{ $employee->dni }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ optional($employee->area)->name }}</td>
                    <td>{{ optional($employee->position)->name }}</td>
                    <td>{{ optional($employee->location)->name }}</td>
                    <td>
                        <span class="badge {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                            {{ $isActive ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-primary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No hay empleados registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="employees-pagination" class="mt-3">
    @if (method_exists($employees, 'links'))
        {{ $employees->onEachSide(1)->links('pagination::bootstrap-5') }}
    @endif
</div>
