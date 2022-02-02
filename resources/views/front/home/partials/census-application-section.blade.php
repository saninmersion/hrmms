<div class="banner__content-form card p-4 md:p-8 xl:p-10 !rounded-md shadow">
    @if(Carbon\Carbon::now()->gt(Carbon\Carbon::make(config('config.deadline'))))
        <span class="bg-blue-100 block py-3 px-4 mb-8 text-sm text-black text-center rounded">
          <b>{{ trans('general.Deadline') }} : </b>
            {{ $deadline['formatted'] }}
        </span>
    @endif
    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::APPLICATION_SUBMISSION || config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::APPLICATION_PROCESSING)
        <login-form v-cloak
                    :districts='@json($districts)'
                    deadline="{{$deadline['raw']}}"
                    icon-link="{{asset('images/icons.svg#')}}"
                    new-login-url="{{ route('front.applicant.login.new') }}"
                    old-login-url="{{ route('front.applicant.login.old', 'edit') }}"
                    check-login-url="{{ route('front.applicant.login.old', 'check') }}">
        </login-form>
    @endif

    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::ENUMERATOR_SHORTLISTED)
        <check-shortlist-status-form v-cloak check-route="{{route('front.applicant.check')}}" check-role="enumerator">
        </check-shortlist-status-form>
    @endif

    @if(config('config.application-stage') === App\Infrastructure\Constants\ApplicationStages::SUPERVISOR_SHORTLISTED)
        <check-shortlist-status-form v-cloak check-route="{{route('front.applicant.check')}}" check-role="supervisor">
        </check-shortlist-status-form>
    @endif
</div>
