@extends('_layout.app')
@section('title', 'Home')
@section('content')
    <x-main-card
        title="Jornada"
        subtitle="Estadisticas"
        mainNumber="38"
        mainLabel="jornadas"
        :items="[
            ['value' => '300', 'label' => 'partidos jugados'],
            ['value' => '80', 'label' => 'partidos'],
            ['value' => '1004', 'label' => 'goles'],
        ]"
        route="/prueba"
        buttonText="Detalles"/>
    <x-main-card></x-main-card>
    <x-main-card></x-main-card>

@endsection