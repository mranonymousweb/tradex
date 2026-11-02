@extends('admin.layout.master')

@section('title', trans('general.chat'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900 flex">
        <!-- Sidebar - Admins List -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mr-6 flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">@lang('general.admins')</h3>

            <input
                    type="text"
                    id="adminSearch"
                    placeholder="@lang('general.search_admin')"
                    class="w-full mb-4 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600
           rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white
           focus:outline-none focus:ring-2 focus:ring-blue-500"
            />


            <ul class="space-y-2" id="admin_list">
                @foreach($admins as $ad)
                    <li class="admin-item" data-name="{{$ad->getFullInfo()}}">
                        <a href="{{route('admin.admin.chat',['admin_id'=>$ad->id])}}"
                           class="flex justify-between items-center p-2 rounded hover:bg-blue-100 dark:bg-blue-900
           {{ $currentAdmin == $ad->id ? 'bg-blue-200 dark:bg-blue-800 font-medium' : '' }}">

                            <span>{{ $ad->getFullInfo() }}</span>

                            @if($ad->unread_count > 0)
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    {{ $ad->unread_count }}
                </span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <!-- Chat Area -->
        <div class="flex-1 bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 flex flex-col">
            <!-- Chat Header -->
            <div class="flex items-center justify-between mb-4 border-b pb-2 dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-white">
                    @lang('general.chat_with') {{ $admins?->firstWhere('id', $currentAdmin ?? 0)?->getFullInfo() ?? '' }}
                </h3>
                <a href="{{ route('admin.dashboard') }}"
                   class="text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white px-3 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    &larr; @lang('general.back_to_dashboard')
                </a>
            </div>
            @if($chats->nextPageUrl())
                <div id="more_button" class="w-full flex justify-center">

                    <button class="bg-blue-100 px-6 text-xs py-2 mb-2 rounded-lg">
                        @lang('general.more_seen')
                    </button>
                </div>
            @endif


            <!-- Messages Scrollable Area -->
            <div class="flex-1 overflow-y-auto space-y-4 mb-4" id="chat_container">
                @forelse($chats->sortBy('id') as $chat)
                    <div class="flex {{ $chat->from_admin_id == $admin->id ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg shadow
                                {{ $chat->from_admin_id == $admin->id
                                    ? 'bg-blue-500 text-white rounded-tr-none'
                                    : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-tl-none' }}">
                            <p class="text-sm break-words whitespace-pre-wrap">
                                {{ $chat->message }}
                            </p>
                            <small class="block mt-1 text-xs opacity-70">
                                {{ $chat->getCreatedAt('Y/m/d - H:i') }}
                            </small>
                            @if($chat->seen_at)
                                @lang('general.seen')
                            @else
                                @lang('general.sand')
                            @endif
                            @if (isset($chat->files) && $chat->files->count() > 0)
                                <div class="mt-2 pt-1 border-t border-white/20">
                                    @foreach($chat->files as $file)
                                        <a href="{{ asset($file->path) }}" target="_blank"
                                           class="text-xs underline hover:no-underline block">{{ $file->name ?? 'file' }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                        @lang('general.no_messages')
                    </div>
                @endforelse
            </div>

            <!-- Message Input  -->
            {{-- ** enctype="multipart/form-data" --}}
            <form action="{{ route('admin.admin.chat.send') }}" method="POST" enctype="multipart/form-data"
                  class="flex items-end gap-2">
                @csrf
                <input type="hidden" name="to_admin_id" value="{{ $currentAdmin ?? null }}">

                <input type="file" name="attachments[]" id="attachments" multiple
                       class="hidden"
                       onchange="document.getElementById('attachment-display').textContent = this.files.length > 0 ? this.files.length + ' @lang('general.files_selected')' : ''">

                @error('attachments')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <label for="attachments"
                       class="cursor-pointer bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition text-sm self-center">
                    @lang('general.attach')
                </label>

                <textarea name="message"
                          rows="1"
                          placeholder="@lang('general.type_message')"
                          class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2
    bg-white dark:bg-gray-700 text-gray-800 dark:text-white
    focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                          oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>

                @error('message')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <div id="attachment-display" class="text-xs text-gray-500 dark:text-gray-400 self-center hidden"></div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    @lang('general.send')
                </button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const messagesContainer = document.getElementById('chat_container');

            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            scrollToBottom();
        });
        let nextPageUrl = "{{ $chats->nextPageUrl()}}";
        const productsContainer = document.getElementById('chat_container');
        let isLoading = false; // Prevent duplicate requests
        const moreButton = document.getElementById('more_button')
        moreButton.addEventListener('click', () => {
            if (!nextPageUrl || isLoading) {
                return;
            }
            isLoading = true;

            fetch(nextPageUrl, {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    if (data.html) {
                        productsContainer.insertAdjacentHTML('afterbegin', data.html);
                        nextPageUrl = data.next_page_url;
                        console.log(nextPageUrl);
                        if (!nextPageUrl) {
                            moreButton.classList.add('hidden');
                        }

                        isLoading = false;
                    } else {
                        nextPageUrl = null;
                    }
                })
                .catch(error => console.error('Error fetching products:', error))
                .finally(() => {
                    isLoading = false;
                });
        });

        const searchInput = document.getElementById('adminSearch');
        const adminItems = document.querySelectorAll('.admin-item');

        searchInput.addEventListener('input', function () {
            const searchValue = this.value.toLowerCase().trim();

            adminItems.forEach(item => {
                const name = item.getAttribute('data-name');

                if (name.includes(searchValue)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>

@endsection
