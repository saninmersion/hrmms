@section('title', __('auth.Change Password'))

<x-auth-layout>
    <x-common.form action="{{ route('password.update') }}">
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-common.input-wrapper name="email">
            <x-common.label for="email" value="{{ __('validation.attributes.email') }}"/>
            <x-common.input type="email"
                            name="email"
                            :value="old('email', $request->email)"
                            readonly/>
            <x-common.form-error name="email"/>
        </x-common.input-wrapper>

        <div class="mt-4">
            <x-common.label for="password" value="{{ __('validation.attributes.password') }}"/>
            <x-common.input type="password"
                            name="password"
                            autocomplete="new-password"
                            autofocus/>
            <x-common.form-error name="password"/>
        </div>

        <div class="mt-4">
            <x-common.label for="password_confirmation"
                            value="{{ __('validation.attributes.password_confirmation') }}"/>
            <x-common.input type="password"
                            name="password_confirmation"
                            autocomplete="new-password"/>
            <x-common.form-error name="password_confirmation"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-common.button class="ml-4">
                {{ __('auth.Reset Password') }}
            </x-common.button>
        </div>
    </x-common.form>
</x-auth-layout>
