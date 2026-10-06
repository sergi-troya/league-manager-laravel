@extends('_layout.app')

@section('title', 'Jornadas')
@section('content')
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md-6">
            <h1 class="h2 text-dark mb-0 fw-bold">Matchday Index</h1>
            <p class="text-muted small mb-0">Resultados y programación por jornada</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="col-12 col-md-6">
            <form action="{{ route('matchday.index') }}" method="GET" class="d-flex justify-content-md-end align-items-center gap-2">
                <div class="min-w-select">
                    <select name="matchday" id="matchday-select" aria-label="Default select example" class="form-select border-secondary-subtle shadow-sm">
                        @foreach ($matchdays as $matchday)
                            <option value="{{ $matchday->number }}" 
                                {{ (isset($currentMatchday) && $currentMatchday->number == $matchday->number) ? 'selected' : '' }}>
                                {{ $matchday->number }} | {{ $matchday->date ? $matchday->date->format('d/m/Y') : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-dark shadow-sm">View Matchday</button>
            </form>
        </div>

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                @if (isset($currentMatchday))
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark text-uppercase fs-7 font-monospace">
                            <tr>
                                <th scope="col" class="ps-4" style="width: 35%">Home Team</th>
                                <th scope="col" class="text-center" style="width: 15%">Result</th>
                                <th scope="col" style="width: 30%">Away Team</th>
                                <th scope="col" class="pe-4 text-end" style="width: 20%">Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($games as $game)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark text-uppercase">{{ $game->homeTeam->short_name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3 py-2 font-monospace fs-6 shadow-sm">
                                            {{ $game->home_goals ?? '-' }} : {{ $game->away_goals ?? '-' }}   
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark text-uppercase">{{ $game->awayTeam->short_name }}</td>
                                    <td class="pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('matchday.game.edit', [$game->matchday_id, $game->id]) }}" class="btn btn-sm btn-secondary px-3 shadow-sm">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-5 text-center text-muted">
                        <p class="mb-0 fs-5">No hay jornadas registradas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection