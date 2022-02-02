<section class=" pb-8 md:pb-12 overflow-hidden">
    <div class="banner__heading">
    </div>
    <div class="container mx-auto">
        <div class="banner__content flex flex-col items-center max-w-xl md:gap-8 mx-auto sm:gap-4 xl:gap-10">

            <div class="xlg:flex items-center justify-between flex-wrap gap-4 mt-10">
                <a href="{{route('front.results.enumerator')}}" class="btn btn--icon !px-4 !py-3 ml-auto">
                    सर्टलिष्ट गरिएका गणकहरुको नामावली
                </a>
            </div>

            <div
                    class="grid  mt-4 sm:mt-0 xlg:grid-cols-none  gap-6 md:gap-8 xlg:gap-6 md:items-start xlg:items-baseline">
                <div class="banner__content-form card p-4 md:p-8 xl:p-10 !rounded-md shadow">
                    <check-enmerator-shortlist-status-form v-cloak check-route="{{route('front.applicant.check')}}"></check-enmerator-shortlist-status-form>

                </div>
            </div>
        </div>
    </div>
</section>
