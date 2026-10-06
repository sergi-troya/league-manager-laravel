@extends('_layout.error')

@section('title', 'Página no encontrada')

@section('content')
    <div class="error-code">404</div>
    <h1 class="error-title">Página no encontrada</h1>
    <p class="error-desc">El recurso o la ruta solicitada no existe o ha sido movida.</p>
    <a href="{{ route('standings.index') }}" class="btn">Volver a la clasificación</a>
@endsection