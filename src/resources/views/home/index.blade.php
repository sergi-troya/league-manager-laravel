@extends('_layout.app')
@section('title', 'Home')
@section('content')
    <div class="card-group">
        <x-main-card title="Jornadas" 
                    subtitle="Estadisticas" 
                    mainNumber="{{ $data['matchday_count'] }}" 
                    mainLabel="jornadas" 
                    :items="[
                        ['value' => $data ['games_completed'], 'label' => 'partidos jugados'],
                        ['value' => $data ['games_pending'], 'label' => 'partidos pendientes'],
                        ['value' => $data ['total_goals'], 'label' => 'goles'],
                    ]"
                    route="{{ $data['url']}}" buttonText="Detalles" />
        <x-main-card title="Equipos" 
                    subtitle="Estadisticas" 
                    mainNumber="{{ $teams_data['teams_count'] }}" 
                    mainLabel="equipos" 
                    :items="{{ $teams_data ['top_teams']}}"
                    route="{{ $teams_data['url']}}" buttonText="Detalles" />
        <x-main-card></x-main-card>
    </div>
@endsection
