@extends('_layout.app')
@section('title', 'Home')
@section('content')
    <div class="card-group">

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
@endsection
