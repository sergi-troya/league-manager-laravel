@extends('_layout.error')

@section('title', 'Método no permitido')

@section('content')
    <div class="error-code">405</div>
    <h1 class="error-title">Método no permitido</h1>
    <p class="error-desc">La acción HTTP solicitada no está permitida en este recurso.</p>
    <a href="{{ route('standings.index') }}" class="btn">Volver a la clasificación</a>
@endsection