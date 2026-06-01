@extends('_layout.app')


@section('content')
    <h1>Mathcday Index</h1>
        <div class="container">
                <form action="{{ route('matchday.index')}}" method="GET" class="row d-flex align-items-end gap-3">
                    <div class="col">
                        <select name="matchday" id="matchday-select" type="form-select" aria-label="Default select example" class="form-select">
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
                <div class="col">
                    <button type="submit" class="btn btn-dark mt-3">View Matchday</button>
                </div>
                </form>
                <div>
                    @if (isset($matchdayData))
                        <table class="table mt-4">
                            <thead>
                                <tr>
                                    <th scope="col">Home Team</th>
                                    <th scope="col">Away Team</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selectedMatchday->games as $game)
                                    <tr>
                                        <td>{{ $game->homeTeam->short_name}}</td>
                                        <td>{{ $game->awayTeam->short_name}}</td>
                                        <td>{{ $game->home_goals ?? '-' }}</td>
                                        <td>{{ $game->away_goals ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('matchday.game.edit', [$game->matchday_id, $game->id])}}" class="btn btn-primary me-3">Editar</a>
                                            <a href="#" class="btn btn-danger me-3">Eliminar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    @endif
                </div>
            
        </div>
@endsection