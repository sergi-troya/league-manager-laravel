@extends('_layout.app')
@section('title', 'Home')
@section('content')
    <div class="container mt-5">
        <div mb-5 text-start>
            <h1 class="fw-bold text-dark display-6 mb-1">Dashboard General</h1>
            <p class="text-muted text-uppercase small font-monospace">Temporada Oficial de Laliga | Estadísticas en vivo</p>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <x-main-card 
                    title="Jornadas" 
                    subtitle="Estadísticas" 
                    :main-number="$data['matchday_count']" 
                    main-label="jornadas" 
                    :items="[
                        ['value' => $data['games_completed'], 'label' => 'partidos jugados'],
                        ['value' => $data['games_pending'], 'label' => 'partidos pendientes'],
                        ['value' => $data['total_goals'], 'label' => 'goles'],
                    ]"
                    :route="$data['url']" 
                    button-text="Detalles" 
                    />
            </div>
            <div class="col-12 col-md-6">
                <x-main-card 
                    title="Equipos" 
                    subtitle="Estadísticas" 
                    :main-number="$teams_data['teams_count']"
                    main-label="equipos" 
                    :items="$teams_data['top_teams']"
                    :route="$teams_data['url']" 
                    button-text="Detalles" 
                    />
            </div>
        </div>
    </div>
@endsection
