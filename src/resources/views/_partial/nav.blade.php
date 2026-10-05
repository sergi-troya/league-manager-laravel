<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
    <div class="container-fluid px-4">

        <!-- Botón hamburguesa (ID corregido: mainNavbar) -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Contenedor colapsable con ID alineado -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-3">
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('home*') ? 'active text-dark border-bottom border-2 border-dark' : 'text-secondary' }}" 
                       href="{{ route('home.index') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('matchday*') ? 'active text-dark border-bottom border-2 border-dark' : 'text-secondary' }}" 
                       href="{{ route('matchday.index') }}">Matchday</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('cities*') ? 'active text-dark border-bottom border-2 border-dark' : 'text-secondary' }}" 
                       href="{{ route('cities.index') }}">Cities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('teams*') ? 'active text-dark border-bottom border-2 border-dark' : 'text-secondary' }}" 
                       href="{{ route('teams.index') }}">Teams</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('standings.*') ? 'active text-dark border-bottom border-2 border-dark' : 'text-secondary' }}"
                       href="{{ route('standings.index') }}">Clasificación</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
