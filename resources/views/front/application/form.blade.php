@section('title', trans('general.page-title.form'))
<x-front-layout>
    @php
    $validationErrors = App\Infrastructure\Support\Helper::getErrors($errors);
    @endphp
    <application-form
        class="scroll-enabled"
        :applicant='@json($applicant)'
        :application-types='@json($applicationTypes)'
        :new-districts='@json($newDistricts)'
        :old-districts='@json($oldDistricts)'
        :grading-systems='@json($gradingSystems)'
        :major-subjects='@json($majorSubjects)'
        :ethnicities='@json($ethnicities)'
        :mother-tongues='@json($motherTongues)'
        :disabilities='@json($disabilities)'
        media-upload-url="{{ route('front.application.files.upload') }}"
        submit-url="{{ route('front.application.form.submit') }}"
        :errors='@json($validationErrors)'
        logout-url="{{ route('front.application.logout') }}"
        how-to-page-url="{{route('front.how-to')}}">
    </application-form>
</x-front-layout>
