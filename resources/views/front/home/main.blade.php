<x-front-layout>
    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::APPLICATION_SUBMISSION)
        // Show Application Process information
        // Show Application Form and Application Status Check Form
        // Show Days Remaining
        <section class=" pb-8 md:pb-12 overflow-hidden">
            @include('front.home.partials.banner')
            <div class="container">
                <div class="banner__content  sm:grid sm:gap-4 md:gap-8 xl:gap-10">
                    @include('front.home.partials.cenus-info-section')
                    @include('front.home.partials.census-application-section')
                </div>
            </div>
        </section>
    @endif

    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::APPLICATION_PROCESSING)
        // Show Application Process Information
        // Show Application Status Check Form
        // Show application is closed. Do not show Days Remaining
    @endif

    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::ENUMERATOR_SHORTLISTED)
        // Show Application Process Information
        // Show Application Status Check Form
        // Show application is closed. Do not show Days Remaining
        // Show Enumerator Shortlisted list view/download link
    @endif

    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::SUPERVISOR_SHORTLISTED)
        // Show Application Process Information
        // Show Application Status Check Form
        // Show application is closed. Do not show Days Remaining
        // Show Enumerator and Supervisor Shortlisted list view/download link
            {{--    @include('front.home.partials.application-check')--}}

    @endif

    <div class="modal-wrap">
        <div class="modal">
                    <span class="ic-close ic-close--modal">
                    </span>
            <div class="modal-img-wrap sm:pr-4">
                <img src="/images/notice.png" alt="population census nepal notice">
            </div>
        </div>
    </div>
    @push('js')
        <script>
            // document.querySelector("body").classList.add("show-modal")
        </script>
    @endpush
</x-front-layout>
