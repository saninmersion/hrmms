@if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::APPLICATION_SUBMISSION)
    <div class="sample__file-wrap  rounded-md grid p-4 md:p-6 gap-6">
        <div class="sample__file-img-wrap rounded-md bg-white overflow-hidden shadow">
            <img class="h-full w-full " src="{{asset('/images/form-sample.png')}}"
                 alt="Census 2077 application form  sample">
        </div>
        <div class="sample__file-txt">
            <h2 class="heading-primary pb-1">
                {{ trans('general.Sample Form') }}
            </h2>

            <p>{{ trans('general.sample-form-help-text') }}</p>

            <a class="btn btn--icon btn--small mt-4"
               href="{{asset('/files/sample-application-form.pdf')}}"
               download class="font-normal !px-2 mt-6">
                <svg class="mr-2 w-4 h-3">
                    <use xlink:href="{{asset('/images/icons.svg#ic-download')}}"></use>
                </svg>
                {{ trans('general.Download') }}
            </a>
        </div>
    </div>
@endif
