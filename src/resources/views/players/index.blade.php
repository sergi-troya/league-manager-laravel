@extends('_layout.app')

@section('title', 'Plantilla de ' . $team->short_name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 text-dark mb-0 fw-bold">Plantilla: {{ $team->full_name }}</h1>
            <p class="text-muted small mb-0">Listado oficial de jugadores inscritos</p>
        </div>
        <a href="{{ route('players.create', $team) }}" class="btn btn-dark px-4 shadow-sm">
            <i class="bi bi-plus-lg"></i> Nuevo Jugador
        </a>
    </div>

    <div class="card shadow-premium border-0 overflow-hidden bg-white">
        <div class="card-body p-0">
            @if (isset($players) && $players->count() > 0)
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark text-uppercase fs-7 font-monospace">
                        <tr>
                            <th scope="col" class="ps-4" style="width: 10%">Dorsal</th>
                            <th scope="col" style="width: 40%">Nombre</th>
                            <th scope="col" style="width: 30%">Posición</th>
                            <th scope="col" class="pe-4 text-end" style="width: 20%">Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($players as $player)
                            <tr>
                                <td class="ps-4 font-monospace fw-black text-secondary">
                                    #{{ $player->number ?? '00' }}
                                </td>
                                
                                <td class="fw-bold text-dark">
                                    {{ $player->name }}
                                </td>
                                
                                <td>
                                    <span class="badge bg-light text-dark border px-2.5 py-1.5 text-uppercase">
                                        {{ $player->position ?? 'No definida' }}
                                    </span>
                                </td>
                                
                                <td class="pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('players.edit', [$team, $player]) }}" class="btn btn-sm btn-secondary px-3 shadow-sm">
                                            Editar
                                        </a>
                                        <form action="{{ route('players.destroy', [$team, $player]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger px-3 shadow-sm" onclick="return confirm('¿Eliminar jugador?')">
                                                Delete
                                            </button>
                                        </form> 
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-5 text-center text-muted">
                    <p class="mb-0 fs-5">No hay jugadores registrados en este equipo todavía.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        {{ $players->links('pagination::bootstrap-5') }}
    </div>
@endsection