<aside class="lg:col-span-1 space-y-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

        <!-- User Profile Section -->
        <div class="p-6 text-center border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
            <div class="relative inline-block">
                <img class="h-24 w-24 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-md mx-auto"
                     src="{{ $user->getAvatar()  }}"
                     alt="{{ $user->fullName() }}">
                <span class="absolute bottom-1 right-1 block h-4 w-4 rounded-full bg-green-500 border-2 border-white dark:border-gray-800" title="Active Status"></span>
            </div>
            <h3 class="mt-4 text-lg font-bold text-gray-900 dark:text-white leading-tight">
                {{ $user->fullName() }}
            </h3>
            <p class="text-xs font-mono text-gray-500 dark:text-gray-400 mt-1 tracking-wider">
                @lang('general.id'): #{{ $user->id }}
            </p>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4">
            <ul class="space-y-1">

                <!-- Account Section -->
                <li class="pb-2 px-3">
                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">@lang('general.account_info')</span>
                </li>

                @can('admin.user.edit')
                    <li>
                        <a href="{{route('admin.user.edit',$user)}}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                       {{ request()->routeIs('admin.user.edit')
                          ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 border-r-4 border-blue-600'
                          : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fa-solid fa-user-gear w-5 text-center text-base"></i>
                            <span>@lang('general.edit')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.user.details.security')
                    <li>
                        <a href="{{route('admin.user.details.security',$user)}}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                       {{ request()->routeIs('admin.user.details.security')
                          ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 border-r-4 border-blue-600'
                          : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fa-solid fa-shield-halved w-5 text-center text-base"></i>
                            <span>@lang('general.security')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.user.details.authentication')
                    <li>
                        <a href="{{ route('admin.user.details.authentication', $user) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                       {{ request()->routeIs('admin.user.details.authentication')
                          ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 border-r-4 border-blue-600'
                          : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fa-solid fa-key w-5 text-center text-base"></i>
                            <span>@lang('general.authentication')</span>
                        </a>
                    </li>
                @endcan

                <!-- Financial Section -->
                <li class="pt-6 pb-2 px-3 border-t border-gray-100 dark:border-gray-700 mt-4">
                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">@lang('general.financials')</span>
                </li>

                @can('admin.reports.wallets')
                    <li>
                        <a href="{{route('admin.reports.wallets',['user'=>$user->id])}}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fa-solid fa-wallet w-5 text-center text-base"></i>
                            <span>@lang('general.wallets')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.user.details.assets')
                    <li>
                        <a href="{{route('admin.user.details.assets',$user)}}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fa-solid fa-chart-pie w-5 text-center text-base"></i>
                            <span>@lang('general.assets')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.user.details.asset.logs')
                    <li>
                        <a href="{{ route('admin.user.details.asset.logs', ['user' => $user->id]) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                       {{ request()->routeIs('admin.user.details.asset.logs') && (string) request('user') === (string) $user->id
                          ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 border-r-4 border-blue-600'
                          : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fa-solid fa-clock-rotate-left w-5 text-center text-base"></i>
                            <span>@lang('general.admin_wallet_changes')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.user.details.manual.transaction')
                    <li>
                        <a href="{{ route('admin.user.details.manual.transaction', $user) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                       {{ request()->routeIs('admin.user.details.manual.transaction', 'admin.user.details.manual.transaction.store', 'admin.user.details.manual.transaction.show')
                          ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 border-r-4 border-blue-600'
                          : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <i class="fa-solid fa-hand-holding-dollar w-5 text-center text-base"></i>
                            <span>@lang('general.manual_transaction')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.user.details.package')
                    <li>
                        <a href="{{route('admin.user.details.package',$user)}}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fa-solid fa-box w-5 text-center text-base"></i>
                            <span>@lang('general.package')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.reports.transaction')
                    <li>
                        <a href="{{route('admin.reports.transaction',['user'=>$user->id,'type'=>\App\Models\UserTransaction::TYPE_DEPOSIT])}}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fa-solid fa-arrow-down-to-bracket w-5 text-center text-base"></i>
                            <span>@lang('general.deposits')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('admin.reports.transaction',['user'=>$user->id,'type'=>\App\Models\UserTransaction::TYPE_WITHDRAW])}}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fa-solid fa-arrow-up-from-bracket w-5 text-center text-base"></i>
                            <span>@lang('general.withdraws')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.swaps.index')
                    <li>
                        <a href="{{route('admin.swaps.index',['user'=>$user->id])}}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fa-solid fa-right-left w-5 text-center text-base"></i>
                            <span>@lang('general.swaps')</span>
                        </a>
                    </li>
                @endcan

                <!-- Support Section -->
                <li class="pt-6 pb-2 px-3 border-t border-gray-100 dark:border-gray-700 mt-4">
                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">@lang('general.support_and_cards')</span>
                </li>

                @can('admin.ticket.index')
                    <li>
                        <a href="{{route('admin.ticket.index',['user'=>$user->id])}}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fa-solid fa-ticket w-5 text-center text-base"></i>
                            <span>@lang('general.tickets')</span>
                        </a>
                    </li>
                @endcan

                @can('admin.card.index')
                    <li>
                        <a href="{{route('admin.card.index',['user' => $user->id])}}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all">
                            <i class="fa-solid fa-credit-card w-5 text-center text-base"></i>
                            <span>@lang('general.cards')</span>
                        </a>
                    </li>
                @endcan

                <!-- Actions -->
                @can('admin.user.enter.panel')
                    <li class="mt-6">
                        <a target="_blank" href="{{route('admin.user.enter.panel',$user)}}"
                           class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 shadow-md shadow-indigo-200 dark:shadow-none transition-all transform active:scale-95">
                            <i class="fa-solid fa-right-to-bracket text-base"></i>
                            <span>@lang('general.login')</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>
</aside>
