@extends('_layout.app')


@section('content')
        <div class="row align-items-center mb-4 g-3">
            <div class="col-12 col-md-6">
                <h1 class="h2 text-dark mb-0 fw-bold">Matchday Index</h1>
                <p class="text-muted small mb-0">Resultados y programación por jornada</p>
            </div>
            <div class="col-12 col-md-6">
                <form action="{{ route('matchday.index')}}" method="GET" class="d-flex justify-content-md-end align-items-center gap-2">
                    <div style="min-width: 250px">
                        <select name="matchday" id="matchday-select" type="form-select" aria-label="Default select example" class="form-select border-secondary-subtle shadow-sm">
                            <option value="">Select Matchday</option>
                            @foreach ($matchdayData as $matchday)
                                <option value="{{ $matchday->id}}" 
                                {{ $matchday->number == $matchday_number ? 'selected' : ''}}>
                                {{$matchday->number}} | {{ $matchday->date->format('d/m/Y')}}
                            </option>
                            @if ($matchday->number == $matchday_number)
                                @php
                                  $selectedMatchday = $matchday;  
                                @endphp
                            @endif
                        @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark shadow-sm">View Matchday</button>
                </form>
            </div>
                <div class="card shadow-sm  border-0 overflow-hidden">
                    <div class="card-body p-0">
                    @if (isset($matchdayData))
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
                                @foreach ($selectedMatchday->games as $game)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark text-uppercase">{{ $game->homeTeam->short_name}}</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border px-3 py-2 font-monospace fs-6 shadow-sm">
                                            {{ $game->home_goals ?? '-' }} : {{ $game->away_goals ?? '-' }}   
                                            </span>
                                        </td>
                                        <td class="fw-bold text-dark text-uppercase">{{ $game->awayTeam->short_name}}</td>
                                        <td class="pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('matchday.game.edit', [$game->matchday_id, $game->id])}}" class="btn btn-sm btn-secondary px-3 shadow-sm">Editar</a>
                                                <a href="#" class="btn btn-sm btn-danger px-3 shadow-sm">Eliminar</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    </div>
                </div>
            
        </div>
@endsection

