<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
    <div class="container-fluid px-4 d-flex align-items-center">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link custom-nav-link" href="{{ route('home.index')}}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link custom-nav-link" href="{{ route('matchday.index')}}">Matchday</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link custom-nav-link" href="#">Players</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link custom-nav-link" href="{{ route('cities.index')}}">Cities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link custom-nav-link" href="#">Teams</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .custom-nav-link {
        font-size: 1.1rem;
        padding: 0.5rem 1rem !important;
        transition: color 0.25s ease-in-out;
        position: relative;
    }

    /* Efecto al pasar el ratón por encima si no está activo */
    .custom-nav-link:hover:not(.active) {
        color: #000000 !important;
    }

    /* Línea estética inferior opcional para el enlace que está activo */
    .custom-nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 1rem;
        right: 1rem;
        height: 2px;
        background-color: #000000; /* El color oscuro que predomina en tu diseño */
        border-radius: 2px;
    }
</style>
