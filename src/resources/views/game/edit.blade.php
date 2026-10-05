@extends('_layout.app')

@section('title', 'Editar partido')
@section('content')
    <div class="container">
        <h1>Editar partido</h1>

        <form action="{{ route('matchday.game.update', ['matchday' => $matchday, 'game' => $game]) }}" method="POST">
            @csrf

            <p><strong>Local:</strong> {{ $game->homeTeam->short_name }}</p>
            <p><strong>Visitante:</strong> {{ $game->awayTeam->short_name }}</p>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="home_goals" class="form-label">Goles local</label>
                <input
                    type="number"
                    id="home_goals"
                    name="home_goals"
                    class="form-control @error('home_goals') is-invalid @enderror"
                    value="{{ old('home_goals', $game->home_goals) }}"
                    min="0"
                    max="2147483647"
                    step="1"
                    required
                >
                @error('home_goals')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="away_goals" class="form-label">Goles visitante</label>
                <input
                    type="number"
                    id="away_goals"
                    name="away_goals"
                    class="form-control @error('away_goals') is-invalid @enderror"
                    value="{{ old('away_goals', $game->away_goals) }}"
                    min="0"
                    max="2147483647"
                    step="1"
                    required
                >
                @error('away_goals')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a class="btn btn-secondary" href="{{ route('matchday.index', ['matchday' => $matchday->id]) }}">
                Volver
            </a>
        </form>
    </div>
@endsection