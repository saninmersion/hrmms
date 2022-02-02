@if (Route::has($route))
    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route($route) }}">
        {{ $text }}
    </a>
@endif
