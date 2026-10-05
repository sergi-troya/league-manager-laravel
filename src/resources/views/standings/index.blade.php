@extends('_layout.app')

@section('title', 'Clasificación')

@section('content')
    {{-- 1. Alerta global de errores colocada arriba del todo --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- 2. Cabecera con título y selector de jornadas (sin alertas dentro) --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
            <p class="text-muted text-uppercase small font-monospace mb-1">LaLiga 2012–2013</p>
            <h1 class="h2 fw-bold text-dark mb-1">Clasificación</h1>
            <p class="text-muted mb-0">
                @if ($selectedMatchday !== null)
                    Acumulado hasta la jornada {{ $selectedMatchday }}
                @else
                    Todos los partidos disputados de la temporada
                @endif
            </p>
        </div>

        <form action="{{ route('standings.index') }}" method="GET">
            <label for="standings-matchday" class="form-label small fw-semibold">Consultar jornada</label>
            <div class="d-flex gap-2">
                <select name="matchday" id="standings-matchday" class="form-select">
                    <option value="" @selected($selectedMatchday === null)>Temporada disputada</option>
                    @foreach ($matchdays as $matchday)
                        <option value="{{ $matchday->number }}" @selected($selectedMatchday === $matchday->number)>
                            Jornada {{ $matchday->number }}@if ($matchday->date) · {{ $matchday->date->format('d/m/Y') }}@endif
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-dark">Consultar</button>
            </div>
        </form>
    </div>

    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <span class="small text-muted me-1">Puestos de referencia:</span>
        <span class="badge text-bg-primary">Champions League · 1º–4º</span>
        <span class="badge text-bg-success">Europa League · 5º–6º</span>
        <span class="badge text-bg-danger">Descenso · 18º–20º</span>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <caption class="px-3 small">
                    Solo cuentan los partidos con resultado completo.
                    Orden: puntos, diferencia de goles y goles a favor.
                </caption>
                <thead class="table-dark small">
                    <tr>
                        <th scope="col" class="ps-3 text-center">Pos.</th>
                        <th scope="col">Equipo</th>
                        <th scope="col" class="text-end"><abbr title="Partidos jugados">PJ</abbr></th>
                        <th scope="col" class="text-end"><abbr title="Partidos ganados">PG</abbr></th>
                        <th scope="col" class="text-end"><abbr title="Partidos empatados">PE</abbr></th>
                        <th scope="col" class="text-end"><abbr title="Partidos perdidos">PP</abbr></th>
                        <th scope="col" class="text-end"><abbr title="Goles a favor">GF</abbr></th>
                        <th scope="col" class="text-end"><abbr title="Goles en contra">GC</abbr></th>
                        <th scope="col" class="text-end"><abbr title="Diferencia de goles">DG</abbr></th>
                        <th scope="col" class="text-end pe-3"><abbr title="Puntos">PTS</abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($standings as $row)
                        <tr @class([
                            'table-primary' => $row['position'] <= 4,
                            'table-success' => $row['position'] >= 5 && $row['position'] <= 6,
                            'table-danger' => $row['position'] >= 18 && $row['position'] <= 20,
                        ])>
                            <th scope="row" class="ps-3 text-center fw-bold">{{ $row['position'] }}</th>
                            <td>
                                <a href="{{ route('teams.show', $row['team']) }}" class="fw-semibold text-dark text-decoration-none">
                                    {{ $row['team']->short_name ?: $row['team']->full_name ?: $row['team']->code }}
                                </a>
                                @if ($row['position'] <= 4)
                                    <span class="badge text-bg-primary ms-2" title="Champions League">Champions</span>
                                @elseif ($row['position'] <= 6)
                                    <span class="badge text-bg-success ms-2" title="Europa League">Europa</span>
                                @elseif ($row['position'] >= 18 && $row['position'] <= 20)
                                    <span class="badge text-bg-danger ms-2" title="Descenso">Descenso</span>
                                @endif
                            </td>
                            <td class="text-end font-monospace">{{ $row['played'] }}</td>
                            <td class="text-end font-monospace">{{ $row['won'] }}</td>
                            <td class="text-end font-monospace">{{ $row['drawn'] }}</td>
                            <td class="text-end font-monospace">{{ $row['lost'] }}</td>
                            <td class="text-end font-monospace">{{ $row['goals_for'] }}</td>
                            <td class="text-end font-monospace">{{ $row['goals_against'] }}</td>
                            <td class="text-end font-monospace">{{ $row['goal_difference'] > 0 ? '+' : '' }}{{ $row['goal_difference'] }}</td>
                            <td class="text-end pe-3 fw-bold font-monospace">{{ $row['points'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">Todavía no hay equipos cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <p class="small text-muted mt-3 mb-0">
        PJ: jugados · PG: ganados · PE: empatados · PP: perdidos · GF: goles a favor · GC: goles en contra · DG: diferencia de goles · PTS: puntos
    </p>
@endsection
