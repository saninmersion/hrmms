<x-front-layout>
    <section class=" pb-8 md:pb-12 overflow-hidden">
        @include('front.home.partials.banner')
        <div class="container">
            <div class="banner__content sm:grid sm:gap-4 md:gap-8 xl:gap-10">
                @include('front.home.partials.census-info-section')

                <div class="grid  mt-4 sm:mt-0 md:grid-cols-2 xlg:grid-cols-none  gap-6 md:gap-8 xlg:gap-6 md:items-start xlg:items-baseline">
                    @include('front.home.partials.census-application-section')

                    @include('front.home.partials.application-sample')
                </div>
            </div>
        </div>
    </section>

    @if(config('config.show-notice'))
        @include('front.home.partials.notice')
    @endif
</x-front-layout>
