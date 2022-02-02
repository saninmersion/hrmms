@props(['disabled' => false, 'name' => null])

<input {{ $disabled ? 'disabled' : '' }} name="{{ $name }}" id="{{ $name }}" {!! $attributes->merge([
    'class' => 'border form-input rounded-md shadow-sm block mt-1 w-full'
]) !!}>
