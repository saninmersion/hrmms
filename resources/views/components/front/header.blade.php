<header class="header  text-white py-4 xlg:py-1">
    <div class="container sm:flex sm:gap-4 sm:flex-wrap xlg:flex-nowrap xlg:justify-between xlg:items-center">
        <div class="flex items-center w-full xlg:w-auto  gap-4">
            <a href="{{ route('front.home') }}" class="flex items-center header__logo ">
                <img src="{{ asset('/images/logo.png') }}" alt="logo">
                <span class="logo__text">
                    {{ trans('general.Nepal Government') }}<br>
                    {{ trans('general.National Planning Commission') }}<br>
                    {{ trans('general.Central Bureau Of Statistics') }}
                </span>
            </a>
            <div class="header__title-wrap mr-auto sm:pl-5 xlg:py-2 hidden xlg:block">
                <h3 class="header__title">
                    <span
                        class="font-extrabold">{{ trans('general.National Census') }}</span> {{ trans('general.2078') }}
                </h3>
                <span class="header__title-desc font-semibold">{{ trans('general.app-name') }}</span>
            </div>

            <div class="!ml-auto flex items-center xlg:hidden">
                <div>
                    <language-switcher set-locale-route="{{ route('locale-set') }}"
                    ></language-switcher>
                </div>
                <button class="burger">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            {{--.mobile nav --}}
            <nav class="nav">
                <div class="flex justify-between bg-primary py-2 px-4 items-center">
                    <div>
                        <h3 class="header__title">
                    <span
                        class="font-extrabold">{{ trans('general.National Census') }}</span> {{ trans('general.2078') }}
                        </h3>
                        <span class="header__title-desc font-semibold">{{ trans('general.app-name') }}</span>
                    </div>
                    <div class="close-menu ic-close ml-auto -mr-2"></div>
                </div>
                <div class="nav-content px-4 flex flex-col">
                    <div>
                        <a href="{{route('front.privacy-policy')}}"
                           class="link block py-4">
                            {{ trans('general.page-title.privacy-policy') }}
                        </a>
                        <a href="{{route('front.terms-and-conditions')}}"
                           class="link block py-4">
                            {{ trans('general.page-title.terms-and-conditions') }}
                        </a>
                    </div>
                    <div class="mt-10 pt-8">
                        @if(app()->getLocale() === 'en')
                            <img class="h-28 w-28 mx-auto" src="{{ asset('/images/logo-2-eng.png') }}"
                                 alt="National population census 2021 Nepal">
                        @else
                            <img class="h-28 w-28 mx-auto" src="{{ asset('/images/logo-2.png') }}"
                                 alt="National population census 2021 Nepal">
                        @endif
                        <div class="text-black !inline-flex w-full justify-center my-6">
                            <days-remaining deadline="{{$deadline}}"></days-remaining>
                        </div>
{{--                        <a href="{{route('front.results.supervisor')}}" class="btn btn--icon mb-4 inline-flex justify-center !py-3 w-full">--}}
{{--                            सर्टलिष्ट गरिएकाको नामावली--}}
{{--                        </a>--}}
                            <a href="{{route('front.results.enumerator')}}" class="btn btn--icon mb-4 inline-flex justify-center !py-3 w-full">
                                सर्टलिष्ट गरिएका गणकहरुको नामावली
                            </a>
                    </div>
                </div>
            </nav>
        </div>
        <div class="hidden xlg:flex  items-center">
            <div class="flex items-center">
                <days-remaining deadline="{{$deadline}}"></days-remaining>
                <div>
                    <language-switcher set-locale-route="{{ route('locale-set') }}"></language-switcher>
                </div>
            </div>
            <div class="flex items-center gap-4 ml-auto">

                @if(app()->getLocale() === 'en')
                    <img class="h-16 w-16 hidden md:block" src="{{ asset('/images/logo-2-eng.png') }}"
                         alt="National population census 2021 Nepal">
                @else
                    <img class="h-16 w-16 hidden md:block" src="{{ asset('/images/logo-2.png') }}"
                         alt="National population census 2021 Nepal">
                @endif
            </div>
        </div>
    </div>
</header>
