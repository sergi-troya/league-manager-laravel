@extends('_layout.app')

@section('title', 'Inscribir Jugador')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-premium border-0 rounded-3 bg-white">
                <div class="card-header bg-dark text-white py-3">
                    <h4 class="mb-0 fs-5 fw-bold">Inscribir Nuevo Jugador</h4>
                    <small class="text-white-50">Añadir a la plantilla de: {{ $team->short_name }}</small>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('players.store', $team) }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label for="number" class="form-label fw-semibold text-secondary small">Dorsal</label>
                                <input type="number" 
                                       name="number" 
                                       id="number" 
                                       class="form-control @error('number') is-invalid @enderror" 
                                       value="{{ old('number') }}" 
                                       placeholder="Ej: 10"
                                       min="1" max="99">
                                @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-5">
                                <label for="name" class="form-label fw-semibold text-secondary small">Nombre Completo</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" 
                                       placeholder="Nombre del futbolista">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="position" class="form-label fw-semibold text-secondary small">Posición</label>
                                <select name="position" id="position" class="form-select @error('position') is-invalid @enderror">
                                    <option value="">Seleccionar...</option>
                                    <option value="PORTER" {{ old('position') == 'PORTER' ? 'selected' : '' }}>PORTER</option>
                                    <option value="DEFENSA" {{ old('position') == 'DEFENSA' ? 'selected' : '' }}>DEFENSA</option>
                                    <option value="MIG" {{ old('position') == 'MIG' ? 'selected' : '' }}>MIG (MEDIO)</option>
                                    <option value="DAVANTER" {{ old('position') == 'DAVANTER' ? 'selected' : '' }}>DAVANTER (DELANTERO)</option>
                                </select>
                                @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="text-secondary-subtle my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('players.index', $team) }}" class="btn btn-secondary px-4 shadow-sm">Cancelar</a>
                            <button type="submit" class="btn btn-dark px-4 shadow-sm">Inscribir Jugador</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection