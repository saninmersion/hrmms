@section('title', __('auth.Login'))

<x-auth-layout>
    <x-common.form action="{{ route('login') }}" class="pt-4">

{{--        <div class="py-2 text-right !text-primary-500 !no-underline monitoring">--}}
{{--            <x-common.text-link route="register" text="{{ __('auth.monitoring_user_registration') }}"/>--}}
{{--        </div>--}}
        <x-common.input-wrapper name="username">
            <x-common.label for="username" value="{{ __('validation.attributes.username') }}"/>
            <x-common.input type="text"
                            name="username"
                            :value="old('username')"
                            autofocus/>
            <x-common.form-error name="username"/>
        </x-common.input-wrapper>

        <div class="mt-4">
            <x-common.label for="password" value="{{ __('validation.attributes.password') }}"/>
            <x-common.input type="password"
                            name="password"
                            autocomplete="current-password"/>
            <x-common.form-error name="password"/>
        </div>

        <div class="block my-4">
            <x-common.checkbox labelClass="flex items-center cursor-pointer" name="remember_me">
                <span class="ml-2 text-sm hover:text-base-2">{{ __('auth.remember_me') }}</span>
            </x-common.checkbox>
        </div>

        <div class="flex items-center justify-end forgot-password mt-6">
            <x-common.text-link route="password.request"
                                text="{{ __('auth.Forgot your password?') }}"/>

            <x-common.button class="ml-6 !py-2.5 !px-5 !text-base !font-medium">
                {{ __('auth.Login') }}
            </x-common.button>
        </div>
    </x-common.form>
</x-auth-layout>
