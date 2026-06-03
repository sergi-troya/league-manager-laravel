@props([
    'title' => 'Default Title',
    'subtitle' => 'Default Subtitle',
    'mainNumber' => '100',
    'mainLabel' => 'Main Label',
    'items' => [],
    'route' => '#',
    'buttonText' => 'View Details',
])

<div class="card h-100 border-0 shadow-premium rounded-3 bg-white overflow-hidden transition-card">
    
    <div class="card-body p-4 pb-0">
        <span class="text-muted text-uppercase font-monospace fs-7 tracking-wider d-block">{{ $subtitle }}</span>
        <h3 class="fw-bold text-dark my-1">{{ $title }}</h3>
        
        <div class="d-flex align-items-baseline mt-4 mb-3">
            <span class="display-4 fw-black text-dark lh-1">{{ $mainNumber }}</span>
            <span class="text-muted ms-2 text-uppercase fs-7 font-monospace fw-bold">{{ $mainLabel }}</span>
        </div>
    </div>

    <ul class="list-group list-group-flush px-4">
        @foreach ($items as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-light px-0 py-2.5">
                <span class="text-secondary text-capitalize small fw-semibold">
                    {{ $item['label'] }}
                </span>
                <span class="fw-bold text-dark font-monospace fs-5">
                    {{ $item['value'] }}
                </span>
            </li>
        @endforeach
    </ul>

    <div class="card-body p-4 mt-auto">
        @if ($route)
            <a href="{{ $route }}" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-bold shadow-sm custom-btn-effect">
                {{ $buttonText }}
            </a>
        @endif
    </div>
</div>