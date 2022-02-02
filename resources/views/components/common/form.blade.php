@props(['method' => 'POST'])

<form method="{{ $method }}" {!! $attributes !!}>
    @csrf

    {{ $slot }}
</form>
