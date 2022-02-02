@if (session('status'))
    <div class="bg-success-50 text-success px-4 py-3 rounded relative mt-6"
         role="alert">
        <span class="block sm:inline"> {{ session('status') }}</span>
    </div>
@endif
