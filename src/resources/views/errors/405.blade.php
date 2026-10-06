@extends('_layout.app')

@section('title', 'Método no permitido')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="display-1 fw-bold text-secondary">405</h1>
            <h2 class="h4 fw-bold text-dark mb-3">Método no permitido</h2>
            <p class="text-muted mb-4">La acción solicitada no está permitida para esta dirección.</p>
            <a href="{{ route('standings.index') }}" class="btn btn-dark px-4 shadow-sm">
                Volver a la clasificación
            </a>
        </div>
    </div>
</div>
@endsection