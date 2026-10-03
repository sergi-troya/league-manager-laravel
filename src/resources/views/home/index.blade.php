@extends('_layout.app')

@section('title', 'Home')

@section('content')
    <div class="mb-5 text-start">
        <h1 class="fw-extrabold text-dark display-6 mb-1" style="font-weight: 800; letter-spacing: -1px;">Dashboard General</h1>
        <p class="text-muted text-uppercase small font-monospace tracking-wider" style="letter-spacing: 0.5px;">
            Temporada Oficial de LaLiga | Estadísticas en vivo
        </p>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <x-main-card 
                title="Jornadas" 
                subtitle="Estadísticas generales" 
                :main-number="$data['matchday_count']" 
                main-label="jornadas" 
                :items="[
                    ['value' => $data['games_completed'], 'label' => 'partidos jugados'],
                    ['value' => $data['games_pending'], 'label' => 'partidos pendientes'],
                    ['value' => $data['total_goals'], 'label' => 'goles anotados'],
                ]"
                :route="$data['url']" 
                button-text="Detalles" 
            />
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <x-main-card 
                title="Equipos" 
                subtitle="Ranking de puntuación" 
                :main-number="$teams_data['teams_count']"
                main-label="equipos" 
                :items="$teams_data['top_teams']"
                :route="$teams_data['url']" 
                button-text="Detalles" 
            />
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <x-main-card
                title="Goleadores" 
                subtitle="Máximos goleadores" 
                :main-number="3" 
                main-label="En el podio" 
                :items="$scorers_data['top_scorers']"
                :route="$scorers_data['url']" 
                button-text="Detalles"
            />   
        </div>
    </div>
@endsection