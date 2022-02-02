@props(['name' => null, 'class' => ''])

<div class="@if($errors->has($name)) has-error @endif {{ $class }}">
    {{ $slot }}
</div>
