@extends('_layout.app')

@section('title', 'Máximos Goleadores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold display-6 mb-1">Máximos Goleadores</h1>
        <p class="text-muted small mb-0">Trofeo Pichichi | Temporada 2012-2013</p>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th scope="col" class="text-center" style="width: 80px;">POS</th>
                    <th scope="col">JUGADOR</th>
                    <th scope="col" class="text-center">PARTIDOS</th>
                    <th scope="col" class="text-center">PENALTIS</th>
                    <th scope="col" class="text-center">MIN / GOL</th>
                    <th scope="col" class="text-end pe-4">GOLES</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scorers as $index => $scorer)
                    <tr>
                        <td class="text-center fw-bold text-muted">
                            {{ $scorers->firstItem() + $index }}
                        </td>
                        <td class="fw-semibold">
                            {{ $scorer->player->name ?? 'Desconocido' }}
                        </td>
                        <td class="text-center text-muted">{{ $scorer->matches }}</td>
                        <td class="text-center text-muted">{{ $scorer->penalties }}</td>
                        <td class="text-center text-muted">{{ $scorer->minutes_per_goal ?? '-' }}'</td>
                        <td class="text-end pe-4">
                            <span class="badge bg-dark rounded-pill px-3 py-2 fs-6">
                                {{ $scorer->goals }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No se encontraron registros de goleadores.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center">
    {{ $scorers->links('pagination::bootstrap-5') }}
</div>
@endsection