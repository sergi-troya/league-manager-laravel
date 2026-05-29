@props([
    'title' => 'Default Title',
    'subtitle' => 'Default Subtitle',
    'mainNumber' => '100',
    'mainLabel' => 'Main Label',
    'items' => [],
    'route' => '#',
    'buttonText' => 'View Details',
])


<div class="card" style="width: 18rem;">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <p class="card-text">{{ $subtitle }}</p>
    </div>
    <div class="card-body">
        <h5 class="card-title" display-4>{{ $mainNumber }}</h5>
        <p class="card-text">{{ $mainLabel }}</p>
    </div>
    <ul class="list-group list-group-flush">
        @foreach ($items as $item)
            <li class="list-group-item">
                <strong>{{ $item['value'] }}</strong>
                {{ $item['label'] }}
            </li>
        @endforeach
    </ul>
    <div class="card-body">
        @if ($route)
            <a href="{{ $route }}" class="btn btn-outline-dark w-100 rounded-4">
                {{ $buttonText }}
            </a>
        @endif
    </div>
</div>