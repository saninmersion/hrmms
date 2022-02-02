@section('title', trans('general.page-title.preview'))
<x-front-layout>
    <application-preview
        :applicant='@json($applicant)'
        :old-districts='@json($oldDistricts)'
        :new-districts='@json($newDistricts)'
        success-message="{{ $successMessage ?? '' }}"
        form-url="{{ route('front.application.form') }}"
        submit-url="{{ route('front.application.form.apply') }}"
        logout-url="{{ route('front.application.logout') }}"
        how-to-page-url="{{route('front.how-to')}}">
    </application-preview>
</x-front-layout>
