<div class="banner__heading">
    <div class="container">
        <div class="grid gap-4 xl:gap-10 py-4 md:py-8">
            <h1 class="banner__title">
                <span class="banner__lg-txt block ">{{ trans('general.homepage-title') }} </span>
            </h1>
            <div class="flex-col gap-4 hidden justify-between self-end xlg:flex">
                <div>
                    <span class="font-bold">
                        {{trans('general.contact_person.title')}}
                    </span>
                    <p class="text-sm">
                        {{trans('general.contact_person.name')}} |
                        {{trans('general.contact_person.position')}}
                    </p>
                    <p class="text-sm">
                        <a class="text-primary-500" href="mailto:{{trans('general.contact_person.email')}}">{{trans('general.contact_person.email')}}</a> |
                        <a class="text-primary-500" href="tel:{{trans('general.contact_person.phone1')}}">{{trans('general.contact_person.phone1')}}</a> |
                        <a class="text-primary-500" href="tel:{{trans('general.contact_person.phone2')}}">{{trans('general.contact_person.phone2')}}</a>
                    </p>
                </div>
                <div class="hidden items-center justify-between xlg:flex">
                    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::APPLICATION_SUBMISSION)
                        <a href="{{route('front.how-to')}}"
                           class="btn btn--small  font-normal">
                            {{ trans('general.how-to-button') }}
                        </a>
                    @endif
                    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::APPLICATION_SUBMISSION)
                        <a href="{{asset('/files/application-notice.pdf')}}" download
                           class="link mt-2 sm:mt-0">
                            <svg class="mr-2.5 !w-3 !h-3.5">
                                <use xlink:href="{{asset('/images/icons.svg#ic-download')}}"></use>
                            </svg>
                            {{ trans('general.homepage-notice') }}
                        </a>
                    @endif
                    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::ENUMERATOR_SHORTLISTED || config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::SUPERVISOR_SHORTLISTED)
                        <a href="{{route('front.results.enumerator')}}"
                           class="btn btn--small mr-2 font-normal">
                            {{ trans('general.shortlist-enumerator-button') }}
                        </a>
                    @endif
                    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::SUPERVISOR_SHORTLISTED)
                        <a href="{{route('front.results.enumerator')}}"
                           class="btn btn--small ml-2 font-normal">
                            {{ trans('general.shortlist-supervisor-button') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
