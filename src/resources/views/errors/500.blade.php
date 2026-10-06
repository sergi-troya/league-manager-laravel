@extends('_layout.error')

@section('title', 'Error del servidor')

@section('content')
    <div class="error-code danger">500</div>
    <h1 class="error-title">Error interno del servidor</h1>
    <p class="error-desc">Ocurrió un problema inesperado. Por favor, inténtalo más tarde.</p>
    <a href="{{ route('standings.index') }}" class="btn">Volver a la clasificación</a>
@endsection