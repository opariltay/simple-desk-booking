@php
$locations = App\Models\Location::orderBy('name', 'ASC')->get();
@endphp

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-t-xl">
            <div class="bg-white border-b border-gray-200">
                
                <div id="accordion-color" data-accordion="collapse" data-active-classes="bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white">
                    @foreach($locations as $location)
                    <h2 id="accordion-color-heading-{{ $location->id }}">
                        <button type="button" class="flex items-center justify-between w-full p-5 font-medium text-left border border-b-0 border-gray-200 dark:border-gray-700 hover:bg-blue-100 dark:hover:bg-gray-800 bg-blue-100 dark:bg-gray-800 text-blue-600 dark:text-white" data-accordion-target="#accordion-color-body-{{ $location->id }}" aria-expanded="true" aria-controls="accordion-color-body-{{ $location->id }}">
                            <span>{{ $location->name }}</span>
                            <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </h2>
                    <div id="accordion-color-body-{{ $location->id }}" class="" aria-labelledby="accordion-color-heading-{{ $location->id }}">
                        <div class="px-5 pb-5 font-light border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                            <x-reservation-calendar></x-reservation-calendar>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>