<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RHNUBE - Contratos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm color-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('employees.index') }}">RHNUBE</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('employees.index') }}">Empleados</a>
                <a class="nav-link" href="{{ route('employees.create') }}">Registrar</a>
                <a class="nav-link" href="{{ route('employees.summary') }}">Resumen</a>
            </div>
        </div>
    </nav>

    <main class="py-2">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Se encontraron errores en el formulario.</strong>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/shared/http.js') }}"></script>
    <script src="{{ asset('js/shared/debounce.js') }}"></script>
    <script src="{{ asset('js/shared/contract-form.js') }}"></script>
    @stack('scripts')
</body>
</html>
