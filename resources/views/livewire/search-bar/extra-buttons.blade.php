<div class="w-full">
    <div class="flex justify-between font-medium duration-200">
        @foreach ($buttons as $item)
            <div class="tooltip tooltip-top p-0" data-tip="{{ $item['title'] }}">
                <a href="{{ route($item['route'], [$item['id']]) }}" title="Assuntos"
                    class="flex py-2 px-3 rounded font-medium transition-colors
                                dark:hover:bg-gray-500 hover:hover:bg-gray-500
                                duration-200 hover:text-white whitespace-nowrap">
                    <svg class="h-6 w-6" viewBox="0 -1.5 20.412 20.412" xmlns="http://www.w3.org/2000/svg">
                        <g id="check-lists" transform="translate(-1.588 -2.588)">
                            <path id="primary" d="M7,4,4.33,7,3,5.5" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                            <path id="primary-2" data-name="primary" d="M3,11.5,4.33,13,7,10" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                            <path id="primary-3" data-name="primary" d="M3,17.5,4.33,19,7,16" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                            <path id="primary-4" data-name="primary" d="M11,6H21M11,12H21M11,18H21" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </g>
                    </svg>
                </a>
            </div>
        @endforeach
    </div>
</div>
