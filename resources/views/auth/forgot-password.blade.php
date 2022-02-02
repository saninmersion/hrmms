@section('title', __('auth.Forget Password'))

<x-auth-layout>

    <div class="my-4 mt-6 text-gray-500" style="font-size: 15px;">
        {{ __('auth.password_reset_help_text') }}
    </div>

    <x-common.form action="{{ route('password.email') }}">
        <x-common.input-wrapper name="email">
            <x-common.label for="email" value="{{ __('validation.attributes.email') }}"/>
            <x-common.input type="email"
                            name="email"
                            :value="old('email')"
                            autofocus/>
            <x-common.form-error name="email" class="block mt-2"/>
        </x-common.input-wrapper>

        <div class="flex items-center justify-end forgot-password mt-6">
            <x-common.text-link route="login" text="{{ __('auth.Login') }}"/>

            <x-common.button class="ml-6 !py-2.5 !px-3 !tracking-normal !text-base !font-medium">
                {{ __('auth.Email Password Reset Link') }}
            </x-common.button>
        </div>
    </x-common.form>
</x-auth-layout>
