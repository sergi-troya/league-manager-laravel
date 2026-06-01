@extends('_layout.app')
@section ('content')
    <div class="container">
        <h1>Game Edit:</h1>
        <form action="{{ route('matchday.game.update', [$game->matchday_id, $game->id])}}" method="POST">
            @csrf
            <input type="text" hidden name="home_team_id" value="{{ $game->home_team_id}}">
            <input type="text" hidden name="away_team_id" value="{{ $game->away_team_id}}">
            <div class="mb-3">
                <label for="HomeTeam" class="form-label">Home Team</label>
                <input type="text" class="form-control" id="HomeTeam" value="{{ $game->homeTeam->short_name}}" name="homeTeam" disabled>
            </div>
            <div class="mb-3">
                <label for="AwayTeam" class="form-label">Away Team</label>
                <input type="text" class="form-control" id="AwayTeam" value="{{ $game->awayTeam->short_name}}" name="awayTeam" disabled>
            </div>
            <div class="mb-3">
                <label for="home_goals" class="form-label">Home Goals</label>
                <input type="text" class="form-control" id="home_goals" value="{{ $game->home_goals}}" name="home_goals" min="0" max="10" required>
            </div>
            <div class="mb-3">
                <label for="home_goals" class="form-label">Away Goals</label>
                <input type="text" class="form-control" id="away_goals" value="{{ $game->away_goals}}" name="away_goals" min="0" max="10" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Send</button>
                    <a class="btn btn-secondary" href="{{ route('matchday.index', $matchday->id)}}">Return</a>
            </div>
        </form>
    </div>
@endsection