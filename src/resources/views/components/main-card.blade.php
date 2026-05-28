@props([
    'title' => 'Default Title',
    'subtitle' => 'Default Subtitle',
    'mainNumber' => '100',
    'mainLabel' => 'Main label',
    'items' => [],
    'route' => '#',
    'buttonText' => 'View Details',
])


<div class="card" style="width: 18rem;">
    <img src="..." class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <p class="card-text">{{ $subtitle }}</p>
    </div>
    <ul class="list-group list-group-flush">
        @foreach ($items as $item)
            <li class="list-group-item"><strong>{{ $item['value'] }}</strong>
                {{ $item['label'] }}</li>
    </ul>
    <div class="card-body">
        @if ($route)
            <a href="{{ $route }}" class="btn btn-outline-dark w-100 rounded-4">
                {{ $buttonText }}
            </a>
        @endif
    </div>
</div>



{{-- <div class="card shadow-sm border-0 h-100">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-1">{{ $title }}</h5>
        <p class="text-muted mb-4">{{ $subtitle }}</p>
        <div class="d-flex align-items-end mb-4">
            <span class="display-3 fw-bold lh-1">{{ $mainNumber }}</span>
            <span class="fs-4 ms-2 mb-2">{{ $mainLabel }}</span>
        </div>
        <div class="mb-4">
            @foreach ($items as $item)
                <p class="">
                    <strong>{{ $item['value'] }}</strong>
                    {{ $item['label'] }}
                </p>
            @endforeach
        </div>
        @if ($route)
            <a href="{{ $route }}" class="btn btn-outline-dark w-100 rounded-4">
                {{ $buttonText }}
            </a>
        @endif
    </div>
</div>
 --}}