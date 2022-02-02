@section('title', trans('general.page-title.results'))
<x-front-layout>
    <main class="info-page">
        <div class="container">
            <div class="md:w-3/4 mx-auto px-2">
                <div class="pt-6 sm:pt-10">
                    <a href="{{route('front.home')}}" class="flex items-center text-primary">
                        <svg class="mr-2 w-5 h-3.5">
                            <use xlink:href="./images/icons.svg#ic-arrow-left"/>
                        </svg>
                        अघिल्लो पृष्ठमा जानुहोस्
                    </a>
                </div>
                <h1 class="heading-primary  !text-primary heading-primary--lg py-6 sm:py-8 lg:pt-10">
                    राष्ट्रिय जनगणना २०७८ को लागि सर्टलिष्ट भएका सुपरिवेक्षकहरुको नामावली
                </h1>
                <div class="text-center">
                    <a href="{{App\Infrastructure\Support\Helper::fileUrl('census_officers.pdf')}}" target="_blank" class="bg-primary-100 text-base flex inline-flex items-center leading-none p-2 px-4 py-3 rounded-full text-black hover:text-primary-500" role="alert">
                        <span class="font-semibold mr-2 text-left flex-auto">प्रदेश तथा जिल्ला जनगणना कार्यालय, जिल्ला जनगणना अधिकारी, सम्पर्क नंं. र ठेगाना</span>
                        <svg class="fill-current opacity-75 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M12.95 10.707l.707-.707L8 4.343 6.586 5.757 10.828 10l-4.242 4.243L8 15.657l4.95-4.95z"/>
                        </svg>
                    </a>
                </div>

                <section class="!pt-0 sm:!pt-2">
                    <div class="grid sm:grid-cols-2">
                        @foreach ($districts as $district)
                            <a href="{{App\Infrastructure\Support\Helper::fileUrl(sprintf('results/%s.pdf', str_replace([' ', '(', ')'], ['-', '', ''], $district['title_en'])))}}" target="_blank"
                               class="results-link py-3">
                                {{ data_get($district, 'title_'. App::currentLocale()) }}
                            </a>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </main>
</x-front-layout>
