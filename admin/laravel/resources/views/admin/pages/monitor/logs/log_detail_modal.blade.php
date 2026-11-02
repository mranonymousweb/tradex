@section('modal')
    <div class="modal fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/40 dark:bg-gray-950/50 backdrop-blur-sm p-4"
         id="detailLogModal">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-lg relative border border-gray-200 dark:border-gray-700 flex flex-col max-h-[70vh]">

            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700 shrink-0">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                    @lang('general.details')
                </h3>
                <button type="button" data-modal-close
                        class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-gray-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="detailLogContent"
                 class="px-4 py-3 overflow-y-auto flex-1 text-sm">
            </div>

            <div class="flex justify-end px-4 py-3 border-t border-gray-200 dark:border-gray-700 shrink-0">
                <button type="button" data-modal-close
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                    @lang('general.close')
                </button>
            </div>
        </div>
    </div>
@endsection
