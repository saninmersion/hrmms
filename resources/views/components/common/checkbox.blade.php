@props([
'disabled' => false,
'labelClass' => ''
])

<label class="{{ $labelClass }}">
    <input
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge(['class' => 'form-checkbox text-secondary border', 'type' => 'checkbox']) !!} >
    {{ $slot }}
</label>
