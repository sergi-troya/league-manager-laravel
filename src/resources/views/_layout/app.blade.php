<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>La liga</title>
    <style>
        /* Grosor ultra-fuerte para los números */
.fw-black { 
    font-weight: 900 !important; 
}

/* Sombreado premium multicapa para bordes limpios y ambientales */
.shadow-premium {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02), 
                0 10px 20px rgba(0, 0, 0, 0.03) !important;
}

/* Comportamiento dinámico al pasar el cursor */
.transition-card {
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), 
                box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.transition-card:hover {
    transform: translateY(-5px);
    /* Al elevarse, la sombra se suaviza y se expande */
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0.06) !important;
}

/* Botón interactivo */
.custom-btn-effect {
    transition: all 0.2s ease-in-out;
}
.custom-btn-effect:hover {
    background-color: #000000;
    color: #ffffff;
    border-color: #000000;
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