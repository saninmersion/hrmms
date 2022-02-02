@props(['name' => null])

@if ($errors->has($name))
    <span class="block mt-2 font-medium text-danger-600 text-sm">
        {{ $errors->first($name) }}
    </span>
@endif
