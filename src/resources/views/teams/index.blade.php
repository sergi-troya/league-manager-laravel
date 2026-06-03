@extends('_layout.app')

@section('title', 'Teams Index')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 text-dark mb-0 fw-bold">Teams Index</h1>
            <p class="text-muted small mb-0">Gestión de clubes y presupuestos de LaLiga</p>
        </div>
        <a href="{{ route('teams.create') }}" class="btn btn-dark px-4 shadow-sm">
            <i class="bi bi-plus-lg"></i> New Team
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            @if (isset($teams) && $teams->count() > 0)
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark text-uppercase fs-7 font-monospace">
                        <tr>
                            <th scope="col" class="ps-4" style="width: 10%">Code</th>
                            <th scope="col" style="width: 25%">Name</th>
                            <th scope="col" style="width: 20%">City</th>
                            <th scope="col" style="width: 25%">Stadium</th>
                            <th scope="col" class="pe-4 text-end" style="width: 20%">Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team)
                            <tr>
                                <td class="ps-4 font-monospace fw-bold text-secondary">
                                    {{ $team->code }}
                                </td>
                                
                                <td>
                                    <div class="fw-bold text-dark">{{ $team->short_name }}</div>
                                    <small class="text-muted text-capitalize">Míster: {{ $team->coach ?? 'Sin asignar' }}</small>
                                </td>
                                
                                <td>
                                    <span class="badge bg-light text-dark border px-2.5 py-1.5">
                                        {{ $team->city ? $team->city->name : 'No asignada' }}
                                    </span>
                                </td>
                                
                                <td class="text-muted small">
                                    {{ $team->stadium ?? '-' }}
                                </td>
                                
                                <td class="pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('teams.edit', $team) }}" class="btn btn-sm btn-secondary px-3 shadow-sm">
                                            Editar
                                        </a>
                                        <a href="{{ route('players.index', $team) }}" class="btn btn-sm btn-info text-white px-3 shadow-sm">
                                            <i class="bi bi-people-fill"></i> Jugadores
                                        </a>
                                        <form action="{{ route('teams.destroy', $team) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger px-3 shadow-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este equipo?')">
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
                    <p class="mb-0 fs-5">No teams registered yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection