<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>La liga</title>
    <style>
        /* Fondo gris ultra suave moderno para que resalten tus tarjetas blancas */
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="flex felx-column min-vh-100">
    <header class="bg-white border-bottom py-3 mb-4">
        <div class="container d-flex align-items-center">
            @include('_partial.logo')
            @include('_partial.nav')
        </div>
    </header>
    <main class="container flex-grow-1 my-4">
        @yield('content')
    </main>
    <footer class="bg-white border-top py-4 text-center mt-auto">
        <div class="container">
            <p class="text-muted mb-0 small">Copyright &copy; 2026 | La liga</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>