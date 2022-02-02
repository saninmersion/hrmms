<div>
    <div class="tab bg-layer bg-white md:hidden p-4 pt-1 shadow">
        <ul class="tab__list flex mb-4">
            <li class="tab__item is--active">  {{ trans('general.Supervisor') }}</li>
            <li class="tab__item"> {{ trans('general.Enumerator') }}</li>
        </ul>
        <div class="tab__content">
            <div class="tab__content-item  is--active">
                <p class="my-4">
                    {{ trans('general.about-text.supervisor.text') }}
                </p>
                <ul class="list">
                    <li>{{ trans('general.about-text.supervisor.list-1') }}</li>
                    <li>{{ trans('general.about-text.supervisor.list-2') }}</li>
                    <li>{{ trans('general.about-text.supervisor.list-3') }}</li>
                </ul>
            </div>
            <div class="tab__content-item">
                <p class="my-4">
                    {{ trans('general.about-text.enumerator.text') }}
                </p>
                <ul class="list">
                    <li>{{ trans('general.about-text.enumerator.list-1') }}</li>
                    <li>{{ trans('general.about-text.enumerator.list-2') }}</li>
                    <li>{{ trans('general.about-text.enumerator.list-3') }}</li>
                </ul>
            </div>
        </div>
    </div>
    <div
            class="banner__content-wrap bg-layer card hidden   md:grid md:grid-cols-2 gap-4 md:gap-0 p-6 lg:p-10 shadow">
        <div class="banner__content-item  md:pr-8  xl:pr-10">
            <h3 class="heading-primary">
                {{ trans('general.Supervisor') }}
            </h3>
            <p class="my-4">
                {{ trans('general.about-text.supervisor.text') }}
            </p>
            <ul class="list">
                <li>{{ trans('general.about-text.supervisor.list-1') }}</li>
                <li>{{ trans('general.about-text.supervisor.list-2') }}</li>
                <li>{{ trans('general.about-text.supervisor.list-3') }}</li>
            </ul>
        </div>
        <div class="banner__content-item md:pl-8  xl:pl-10 ">
            <h3 class="heading-primary">
                {{ trans('general.Enumerator') }}
            </h3>
            <p class="my-4">
                {{ trans('general.about-text.enumerator.text') }}
            </p>
            <ul class="list">
                <li>{{ trans('general.about-text.enumerator.list-1') }}</li>
                <li>{{ trans('general.about-text.enumerator.list-2') }}</li>
                <li>{{ trans('general.about-text.enumerator.list-3') }}</li>
            </ul>
        </div>
    </div>
    <div
            class="card-shadow-wrap transition overflow-hidden relative  card card--gray md:p-8 xl:py-12 xl:px-10  p-4 md:p-6 mt-4 md:mt-5">
        <h3 class="heading-primary">
            {{ trans('general.requirement_for_online_application') }}
        </h3>

        <ul class="mt-4 md:mt-8 pl-4 list">
            <li class="mb-4">{{ trans('general.requirement.list-1') }}</li>
            <li class="mb-4">{{ trans('general.requirement.list-2') }}</li>
            <li class="mb-4">{{ trans('general.requirement.list-3') }}</li>
            <li class="mb-4">{{ trans('general.requirement.list-6') }}</li>
            <li class="mb-4">{{ trans('general.requirement.list-4') }}</li>
            <li class="mb-4">{{ trans('general.requirement.list-5') }}</li>
            <li class="mb-4">{{ trans('general.requirement.list-7') }}</li>
        </ul>
        <span class="card-shadow  h-28  w-full items-end justify-center">
                <button class="text-primary   text-sm font-semibold  mb-6  flex items-center flex-col">{{trans('general.read_more')}}</button>
                        </span>
    </div>
</div>
