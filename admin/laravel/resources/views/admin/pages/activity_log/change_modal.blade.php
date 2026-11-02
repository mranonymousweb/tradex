
@section('modal')


    <div class="modal fixed inset-0 z-50 hidden items-center justify-center
            bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md"
         id="changesModal">

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg
                 max-w-lg relative" style="width: 50%">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b
                    border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    @lang('general.changes')
                </h3>

                <button type="button" data-modal-close
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div id="changesModalContent"
                 class="px-6 py-4 space-y-2 max-h-[60vh] overflow-y-auto text-sm">
                {{-- injected content --}}
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-2 px-6 py-4 border-t
                    border-gray-200 dark:border-gray-700">
                <button type="button" data-modal-close
                        class="px-4 py-2 text-sm rounded-md
                           bg-gray-100 text-gray-700 hover:bg-gray-200
                           dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    @lang('general.close')
                </button>
            </div>

        </div>
    </div>





@endsection

