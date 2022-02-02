@props(['name' => 'error'])

@if($errors->has($name))
    <div class="text-warning bg-danger-100 px-4 py-2.5 rounded-md relative my-6" role="alert">
        <span class="block sm:inline"> {{ $errors->first($name) }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3"/>
    </div>
@endif



