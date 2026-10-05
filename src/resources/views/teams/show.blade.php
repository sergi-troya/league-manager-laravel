@extends('_layout.app')

@section('title', e($team->short_name ?: $team->full_name ?: $team->code))

@section('content')
    <nav aria-label="Ruta de navegación" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('standings.index') }}">Clasificación</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $team->short_name ?: $team->code }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <p class="text-muted text-uppercase small font-monospace mb-1">LaLiga 2012–2013 · {{ $team->code }}</p>
            <h1 class="h2 fw-bold mb-1">{{ $team->short_name ?: $team->full_name ?: $team->code }}</h1>
            <p class="text-muted mb-4">{{ $team->full_name ?: 'Ficha del equipo' }}</p>

            <dl class="row mb-4">
                <dt class="col-sm-3">Ciudad</dt>
                <dd class="col-sm-9">{{ $team->city?->name ?? 'Sin datos' }}</dd>
                <dt class="col-sm-3">Entrenador</dt>
                <dd class="col-sm-9">{{ $team->coach ?: 'Sin datos' }}</dd>
                <dt class="col-sm-3">Estadio</dt>
                <dd class="col-sm-9">{{ $team->stadium ?: 'Sin datos' }}</dd>
                <dt class="col-sm-3">Jugadores</dt>
                <dd class="col-sm-9">{{ $team->players_count }}</dd>
            </dl>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('players.index', ['team' => $team]) }}" class="btn btn-dark">Ver plantilla</a>
                <a href="{{ route('standings.index') }}" class="btn btn-outline-dark">Volver a clasificación</a>
            </div>
        </div>
    </div>
@endsection
