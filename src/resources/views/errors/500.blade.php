@extends('_layout.app')

@section('title', 'Error del servidor')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="display-1 fw-bold text-danger">500</h1>
            <h2 class="h4 fw-bold text-dark mb-3">Error interno del servidor</h2>
            <p class="text-muted mb-4">Ha ocurrido un problema inesperado. Por favor, inténtalo más tarde.</p>
            <a href="{{ route('standings.index') }}" class="btn btn-dark px-4 shadow-sm">
                Volver al inicio
            </a>
        </div>
    </div>
</div>
@endsection