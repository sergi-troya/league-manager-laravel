@extends('_layout.app')


@section('content')
    <div class="container mt5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 text-dark mb-0 fw-bold">Cities Index</h1>
            <a href="{{ route('cities.create') }}" class="btn btn-dark px-4 shadow-sm">
                <i class="bi bi-plus-lg">New City</i>
            </a>
        </div>
        <div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                {{-- Tabla con las cuidades --}}
                @if (isset($cities))
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark text-uppercase fs-7 font-monospace">
                            <tr>
                                <th scope="col" class="ps-4" style="width: 10%">Id</th>
                                <th scope="col" style="width: 35%">Name</th>
                                <th scope="col" style="width: 25%">Population</th>
                                <th scope="col" class="pe-4 text-end" style="width: 30%">Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cities as $city)
                                <tr>
                                    <td class="ps-4 fw-semibold text-secondary">{{ $city->id }}</td>
                                    <td class="fw-bold text-dark">{{ $city->name }}</td>
                                    <td class="fw-bold text-dark">{{ $city->population }}</td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('cities.edit', [$city]) }}"
                                                class="btn btn-sm btn-secondary px-3 shadow-sm">Editar</a>
                                            <form action="{{ route('cities.destroy', [$city]) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <input type="submit" value="Delete"
                                                    class="btn btn-sm btn-danger px-3 shadow-sm"
                                                    onclick="return confirm('Delete delete?')">
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-5 text-center text-muted">
                        <p class="mb-0 fs-5">No cities.</p>
                    </div>
                @endif
            </div>
            <div class="mt-3">
                {{ $cities->links('pagination::bootstrap-5') }}
            </div>
            <!-- Bloque Izquierdo: Ir al Inicio y Anterior -->
            <div class="btn-group" role="group" aria-label="Navegación Izquierda">
                <!-- Primera Página -->
                <a class="btn btn-light border {{ $cities->onFirstPage() ? 'disabled' : '' }}" href="{{ $cities->url(1) }}"
                    title="Primera página">
                    « First
                </a>
                <!-- Página Anterior -->
                <a class="btn btn-light border {{ $cities->onFirstPage() ? 'disabled' : '' }}"
                    href="{{ $cities->onFirstPage() ? '#' : $cities->previousPageUrl() }}">
                    < Previous </a>
            </div>

            <!-- Opcional: Texto informativo central -->
            <small class="text-muted">
                Página {{ $cities->currentPage() }} de {{ $cities->lastPage() }}
            </small>

            <!-- Bloque Derecho: Siguiente y Última Página -->
            <div class="btn-group" role="group" aria-label="Navegación Derecha">
                <!-- Página Siguiente -->
                <a class="btn btn-light border {{ !$cities->hasMorePages() ? 'disabled' : '' }}"
                    href="{{ !$cities->hasMorePages() ? '#' : $cities->nextPageUrl() }}">
                    Next >
                </a>
                <!-- Última Página -->
                <a class="btn btn-light border {{ !$cities->hasMorePages() ? 'disabled' : '' }}"
                    href="{{ $cities->url($cities->lastPage()) }}" title="Última página">
                    Last »
                </a>
            </div>
        </div>
    </div>
@endsection
