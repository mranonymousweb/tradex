@extends('admin.layout.master')
@push('styles')
    <style>
        @keyframes bounce-short {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
            }
        }

        .animate-bounce-short {
            animation: bounce-short 0.5s ease-in-out;
        }

        /* Custom Scrollbar for Chat */
        #chat-container::-webkit-scrollbar {
            width: 4px;
        }

        #chat-container::-webkit-scrollbar-track {
            background: transparent;
        }

        #chat-container::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        .dark #chat-container::-webkit-scrollbar-thumb {
            background: #374151;
        }
    </style>
@endpush

@section('title', trans('master.ticket'))

@section('contents')
    <main class="flex-1 h-[100svh] flex flex-col bg-gray-50 dark:bg-gray-900 overflow-hidden">

        <!-- Top Navigation & Info (Fixed) -->
        <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <!-- User Info Bar -->
            <div class="px-4 py-3 flex items-center justify-between">
                <a href="{{ route('admin.user.edit', $user) }}" class="flex items-center group">
                    <div class="relative">
                        <img src="{{ $user->getAvatar() }}"
                            class="h-10 w-10 rounded-full border-2 border-primary-500 transition-transform group-hover:scale-105"
                            alt="avatar">
                        <span
                            class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-green-500 border-2 border-white dark:border-gray-800"></span>
                    </div>
                    <div class="mr-3">
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors">
                            {{ $user->getFullName() }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">@lang('general.id'):
                            #{{ $user->id }}</p>
                    </div>
                </a>
                {{-- Actions --}}
                <div class="ms-auto">
                    <form action="{{ route('admin.ticket.updateStatus', $ticket) }}" method="post"
                        class="flex flex-wrap items-end gap-2">
                        @csrf
                        @method('PUT')

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs mb-1 text-gray-500 dark:text-gray-400">
                                @lang('general.status')
                            </label>
                            <select name="status"
                                class="min-w-[170px] select2 px-3 py-2 rounded-lg border border-gray-300
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                       focus:ring-primary focus:border-primary">
                                @foreach ($ticket->getStatusData() as $s)
                                    <option value="{{ $s['type'] }}" @selected($ticket->status === $s['type'])>
                                        @lang($s['label'])
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Department --}}
                        <div>
                            <label class="block text-xs mb-1 text-gray-500 dark:text-gray-400">
                                @lang('general.department')
                            </label>
                            <select name="departments"
                                class="select2 px-3 py-2 rounded-lg border border-gray-300
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                       focus:ring-primary focus:border-primary">
                                @foreach ($departments as $d)
                                    <option value="{{ $d->id }}" @selected($ticket->department_id === $d->id)>
                                        {{ $d->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit --}}
                        <button  type="submit" onclick="return confirm('@lang('general.are_you_sure')')"
                            class="px-3 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white text-sm
                                   hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600">
                            @lang('general.change_status_ticket')
                        </button>
                    </form>
                </div>
                @if ($ticket->status !== \App\Models\Ticket::STATUS_CLOSED)
                    <button onclick="closeTicket('{{ route('admin.ticket.ticket.close', $ticket->id) }}')"
                        class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all">
                        <svg class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        @lang('general.close_ticket')
                    </button>
                @endif
            </div>

            <!-- Ticket Summary Bar -->
            <div
                class="px-4 py-2 bg-gray-50/50 dark:bg-gray-900/50 flex flex-wrap gap-4 items-center border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ $ticket->title }}</h2>
                    <span class="text-xs text-gray-400 font-mono">#{{ $ticket->id }}</span>
                </div>
                <div class="flex items-center gap-3 mr-auto">
                    {!! $ticket->getStatus() !!}
                    <span class="text-xs text-gray-500 border-r pr-3 border-gray-300">@lang('master.department'):
                        {{ $ticket->department->getName() }}</span>
                </div>
            </div>
        </header>

        <!-- Chat Body (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 scroll-smooth" id="chat-container">
            @foreach ($messages as $message)
                @if ($message->from == 'admin')
                    <!-- Admin Message (Left) -->
                    <div class="flex items-start gap-3 max-w-[85%] lg:max-w-[70%]">
                        <img src="{{ $admin->getAvatar() }}" class="h-8 w-8 rounded-full shadow-sm" alt="admin">
                        <div class="space-y-1">
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl rounded-tr-none p-4 shadow-sm border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200 text-sm leading-relaxed">
                                {!! nl2br(e($message->message)) !!}

                                @if ($message->files)
                                    <div class="mt-3 grid grid-cols-1 gap-2">
                                        @foreach ($message->files as $file)
                                            <a href="{{ asset($file->path) }}" target="_blank"
                                                class="flex items-center p-2 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 transition-colors border border-gray-200 dark:border-gray-600 group">
                                                <div
                                                    class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded text-primary-600">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="mr-3">
                                                    <p class="text-xs font-bold truncate max-w-[150px]">{{ $file->name }}
                                                    </p>
                                                    <p class="text-[10px] text-gray-500">
                                                        {{ number_format($file->size / 1024, 1) }} KB</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="flex justify-end gap-5">
                                <span
                                    class="text-[10px] text-gray-400 font-mono pr-2">{{ $message->getCreatedAt() }}</span>
                                @if ($message->from == 'admin' && $message->user_id == auth()->user()->id)
                                    {{-- Edit --}}
                                    <button
                                        onclick="openEditModal({{ $message->id }}, `{{ addslashes($message->message) }}`)"
                                        class="text-blue-500 hover:text-blue-700 text-xs">
                                        ✏️
                                    </button>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.ticket.message.delete', $message) }}" method="POST"
                                        onsubmit="return confirm('@lang('general.are_you_sure')')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="text-red-500 hover:text-red-700 text-xs">
                                            🗑
                                        </button>

                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <!-- User Message (Right) -->
                    <div class="flex items-start gap-3 justify-end max-w-[85%] lg:max-w-[70%] mr-auto">
                        <div class="space-y-1">
                            <div
                                class="bg-primary-600 rounded-2xl rounded-tl-none p-4 shadow-md text-white text-sm leading-relaxed">
                                {!! nl2br(e($message->message)) !!}

                                @if ($message->files)
                                    <div class="mt-3 grid grid-cols-1 gap-2">
                                        @foreach ($message->files as $file)
                                            <a href="{{ asset($file->path) }}" target="_blank"
                                                class="flex items-center p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all group border border-white/20">
                                                <div class="p-2 bg-white/20 rounded">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                </div>
                                                <div class="mr-3">
                                                    <p class="text-xs font-bold truncate max-w-[150px]">{{ $file->name }}
                                                    </p>
                                                    <p class="text-[10px] text-primary-100">
                                                        {{ number_format($file->size / 1024, 1) }} KB</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="text-left">
                                <span
                                    class="text-[10px] text-gray-400 font-mono pl-2">{{ $message->getCreatedAt() }}</span>
                            </div>
                        </div>
                        <img src="{{ $user->getAvatar() }}" class="h-8 w-8 rounded-full shadow-sm" alt="user">
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Message Input (Bottom) -->
        <footer
            class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4 shadow-[0_-4px_12px_rgba(0,0,0,0.03)]">
            <form action="{{ route('admin.ticket.chat.create', $ticket) }}" method="post" enctype="multipart/form-data"
                class="max-w-4xl mx-auto">
                @csrf
                <div class="relative flex items-end gap-3">
                    <!-- Attachments Preview (Will be handled by JS) -->
                    <div id="file-list-preview" class="absolute bottom-full mb-2 w-full flex flex-wrap gap-2"></div>

                    <div class="relative flex-1 group">
                        <textarea @if ($ticket->status == \App\Models\Ticket::STATUS_CLOSED) disabled @endif name="description" id="message-input" rows="1"
                            placeholder="@lang('general.ticket.write_your_message')"
                            class="w-full max-h-40 overflow-y-auto pr-4 pl-12 py-3 bg-gray-50 dark:bg-gray-700 border-none rounded-2xl focus:ring-2 focus:ring-primary-500 dark:text-white transition-all resize-none shadow-inner">{{ old('description') }}</textarea>

                        <label for="file-upload"
                            class="absolute left-3 bottom-2.5 p-2 text-gray-400 hover:text-primary-500 cursor-pointer transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </label>
                        <input name="files[]" type="file" id="file-upload" class="hidden" multiple
                            onchange="previewFiles()">
                    </div>

                    @if ($ticket->status != \App\Models\Ticket::STATUS_CLOSED)
                        <button id="send-btn"
                            class="mb-1 p-3 bg-primary-600 text-white rounded-2xl hover:bg-primary-700 shadow-lg shadow-primary-200 dark:shadow-none transition-all active:scale-90">
                            <svg class="h-6 w-6 transform rotate-45" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    @endif
                </div>
            </form>
        </footer>

    </main>
    <div id="editModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 rounded-xl w-full max-w-md p-5">

            <h3 class="font-bold mb-3 text-gray-900 dark:text-white">
                ویرایش پیام
            </h3>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <textarea name="message" id="editMessage" class="w-full border rounded-lg p-3 dark:bg-gray-700 dark:text-white"
                    rows="4"></textarea>

                <div class="flex justify-end gap-2 mt-3">

                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 rounded-lg">
                        لغو
                    </button>

                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg">
                        ذخیره
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection

@section('scripts')
    <script>
        const textarea = document.getElementById('message-input');
        const chatContainer = document.getElementById('chat-container');

        // Auto-resize textarea
        function autoResize() {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }
        textarea.addEventListener('input', autoResize);

        // Scroll to bottom on load
        window.onload = () => {
            chatContainer.scrollTop = chatContainer.scrollHeight;
            autoResize();
        };

        // File Preview Functionality
        function previewFiles() {
            const input = document.getElementById('file-upload');
            const preview = document.getElementById('file-list-preview');
            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    const div = document.createElement('div');
                    div.className =
                        'bg-white dark:bg-gray-800 px-3 py-1.5 rounded-full border border-primary-200 dark:border-primary-900 text-xs flex items-center gap-2 shadow-sm animate-bounce-short';
                    div.innerHTML =
                        `<svg class="w-3 h-3 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg> ${file.name.substring(0, 10)}...`;
                    preview.appendChild(div);
                });
            }
        }
    </script>
    <script>
        function openEditModal(id, message) {

            const modal = document.getElementById('editModal');
            const textarea = document.getElementById('editMessage');
            const form = document.getElementById('editForm');

            textarea.value = message;

            // گرفتن route با placeholder
            let url = "{{ route('admin.ticket.message.update', ':id') }}";

            // جایگزینی id
            url = url.replace(':id', id);

            form.action = url;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>


@endsection
