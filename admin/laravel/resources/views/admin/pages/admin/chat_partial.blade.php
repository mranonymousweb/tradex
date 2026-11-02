@foreach($chats->sortBy('id') as $chat)
    <div class="flex {{ $chat->from_admin_id == $admin->id ? 'justify-end' : 'justify-start' }}">
        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg shadow
                                {{ $chat->from_admin_id == $admin->id
                                    ? 'bg-blue-500 text-white rounded-tr-none'
                                    : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-tl-none' }}">
            <p class="text-sm">{{ $chat->message }}</p>
            <small class="block mt-1 text-xs opacity-70">
                {{ $chat->getCreatedAt('Y/m/d - H:i') }}
            </small>

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
@endforeach
