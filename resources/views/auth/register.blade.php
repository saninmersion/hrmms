<x-auth-layout>
    {{--    <form method="POST" action="{{ route('register') }}">--}}
    {{--        @csrf--}}
    {{--        --}}
    {{--        <div>--}}
    {{--            <x-jet-label for="name" value="{{ __('validation.attributes.fullname') }}" />--}}
    {{--            <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />--}}
    {{--        </div>--}}
    {{--        --}}
    {{--        <div class="mt-4">--}}
    {{--            <x-jet-label for="email" value="{{ __('validation.attributes.email') }}" />--}}
    {{--            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />--}}
    {{--        </div>--}}
    {{--        --}}
    {{--        <div class="mt-4">--}}
    {{--            <x-jet-label for="username" value="{{ __('validation.attributes.username') }}" />--}}
    {{--            <x-jet-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required />--}}
    {{--        </div>--}}
    {{--        --}}
    {{--        <div class="mt-4">--}}
    {{--            <x-jet-label for="password" value="{{ __('validation.attributes.password') }}" />--}}
    {{--            <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />--}}
    {{--        </div>--}}
    {{--        --}}
    {{--        <div class="mt-4">--}}
    {{--            <x-jet-label for="password_confirmation" value="{{ __('validation.attributes.password_confirmation') }}" />--}}
    {{--            <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />--}}
    {{--        </div>--}}
    {{--        --}}
    {{--        <div class="flex items-center justify-end mt-4">--}}
    {{--            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">--}}
    {{--                {{ __('Already registered?') }}--}}
    {{--            </a>--}}
    {{--            --}}
    {{--            <x-jet-button class="ml-4">--}}
    {{--                {{ __('Register') }}--}}
    {{--            </x-jet-button>--}}
    {{--        </div>--}}
    {{--    </form>--}}
    <x-common.form method="POST" action="{{ route('register') }}">
        @csrf

        <x-common.input-wrapper name="name" class="mt-2">
            <x-common.label for="name" value="{{ __('validation.attributes.fullname') }}"/>
            <x-common.input type="text"
                            name="name"
                            :value="old('name')"
                            autofocus/>
            <x-common.form-error name="name"/>
        </x-common.input-wrapper>

        <x-common.input-wrapper name="email" class="my-4">
            <x-common.label for="email" value="{{ __('validation.attributes.email') }}"/>
            <x-common.input type="text"
                            name="email"
                            :value="old('email')"
                            autofocus/>
            <x-common.form-error name="email"/>
        </x-common.input-wrapper>

        <x-common.input-wrapper name="username">
            <x-common.label for="username" value="{{ __('validation.attributes.username') }}"/>
            <x-common.input type="text"
                            name="username"
                            :value="old('username')"
                            autofocus/>
            <x-common.form-error name="username"/>
        </x-common.input-wrapper>

        <x-common.input-wrapper name="password" class="my-4">
            <x-common.label for="password" value="{{ __('validation.attributes.password') }}"/>
            <x-common.input type="password"
                            name="password"
                            autocomplete="new-password"/>
            <x-common.form-error name="password"/>
        </x-common.input-wrapper>

        <x-common.input-wrapper name="password_confirmation">
            <x-common.label for="password_confirmation"
                            value="{{ __('validation.attributes.password_confirmation') }}"/>
            <x-common.input type="password"
                            name="password_confirmation"
                            autocomplete="new-password"/>
            <x-common.form-error name="password_confirmation"/>
        </x-common.input-wrapper>


        <div class="flex items-center justify-end forgot-password mt-6">
            <x-common.text-link route="login"
                                text="{{__('auth.already_register') }}"/>
            <x-common.button class="ml-4 md:ml-6 !py-2.5 !px-4 md:!px-5 !text-base !font-medium tracking-wide">
                                {{ __('auth.register') }}
            </x-common.button>
        </div>
    </x-common.form>

</x-auth-layout>
