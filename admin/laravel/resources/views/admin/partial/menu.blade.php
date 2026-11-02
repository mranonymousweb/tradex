<nav class="space-y-1">
    <!-- Dashboard -->
    <a class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}"
       href="{{route('admin.dashboard')}}">
        <i class="fa-light fa-home p-2"></i>
        @lang('general.dashboard')
    </a>
    <!-- Admins -->
    @canany(['admin.admin.index','admin.roles.index','admin.activity.log.index'])
        <div class="space-y-1">
            <button type="button"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.admin.index','admin.admin.create','admin.admin.edit') ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}"
                    onclick="toggleMenu('{{ $menuPrefix }}-admin-menu', '{{ $menuPrefix }}-admin-arrow-icon')">
                            <span class="flex items-center">
                            <i class="fa-light fa-users p-2"></i>
                            @lang('master.admins')
                           </span>
                <i id="{{ $menuPrefix }}-admin-arrow-icon" class="fa-light fa-plus"></i>
            </button>
            <div id="{{ $menuPrefix }}-admin-menu" class="hidden ml-6 space-y-1">

                @can('admin.admin.index')
                    <a href="{{ route('admin.admin.index') }}"
                       class="block text-xs px-8 py-2 rounded {{ request()->routeIs('admin.admin.index','admin.admin.create','admin.admin.edit') ? 'text-primary dark:text-primary-dark' : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i id="admin-arrow-icon" class="fa-light fa-chevron-left p-2"></i>
                        @lang('master.save_list')
                    </a>
                @endcan
                @can('admin.roles.index')
                    <a href="{{ route('admin.roles.index') }}"
                       class="block text-xs px-8 py-2 rounded {{ request()->routeIs('admin.roles.index','admin.roles.edit','admin.roles.create') ? 'text-primary dark:text-primary-dark' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i id="admin-arrow-icon" class="fa-light fa-chevron-left p-2"></i>
                        @lang('master.roles')
                    </a>
                @endcan
                @can('admin.activity.log.index')
                    <a href="{{ route('admin.activity.log.index') }}"
                       class="block text-xs px-8 py-2 rounded {{ request()->routeIs('admin.activity.log.index') ? 'text-primary dark:text-primary-dark' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i id="admin-arrow-icon" class="fa-light fa-chevron-left p-2"></i>
                        @lang('master.admin_activity_log')
                    </a>
                @endcan

            </div>
        </div>
    @endcanany
    <!-- Users -->
    @canany(['admin.user.index','admin.user.authentication'])
        <div class="space-y-1">


            <button type="button"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.user.index','admin.user.add','admin.user.edit','admin.user.security','admin.user.assets','admin.user.authentication','admin.user.package','admin.user.authentications','admin.user.details.asset.logs') ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}"
                    onclick="toggleMenu('{{ $menuPrefix }}-user-menu', '{{ $menuPrefix }}-user-arrow-icon')">
                        <span class="flex items-center">
                        <i class="fa-light fa-user p-2"></i>
                        @lang('master.users')
                         </span>
                <i id="{{ $menuPrefix }}-user-arrow-icon" class="fa-light fa-plus"></i>
            </button>

            <div id="{{ $menuPrefix }}-user-menu"
                 class="@if(! request()->routeIs('admin.user.index','admin.user.add','admin.user.edit','admin.user.security','admin.user.assets','admin.user.package','admin.user.authentication','admin.user.authentications','admin.user.details.asset.logs')) hidden @endif ml-6 space-y-1">
                @can('admin.user.index')
                    <a href="{{ route('admin.user.index') }}"
                       class="block text-xs px-8 py-2 rounded {{ request()->routeIs('admin.user.index','admin.user.add','admin.user.edit','admin.user.security','admin.user.assets','admin.user.package') ? 'text-primary dark:text-primary-dark' : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('master.users')
                    </a>
                @endcan
                @can('admin.user.authentication')
                    <a href="{{ route('admin.user.authentication') }}"
                       class="block text-xs px-8 py-2 rounded {{ request()->routeIs('admin.user.authentication','admin.user.authentications') ? 'text-primary dark:text-primary-dark' : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.waiting_users')
                    </a>
                @endcan
                @can('admin.user.details.asset.logs')
                    <a href="{{ route('admin.user.details.asset.logs') }}"
                       class="block text-xs px-8 py-2 rounded {{ request()->routeIs('admin.user.details.asset.logs') ? 'text-primary dark:text-primary-dark' : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.change_asset_by_admin')
                    </a>
                @endcan
            </div>
        </div>
    @endcanany


    @canany(['admin.bank.index','admin.card.index'])
        <!-- User Cards -->
        <div class="space-y-1">
            <button type="button"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg
            {{ request()->routeIs(
                'admin.bank.index',
                'admin.bank.add',
                'admin.bank.details',
                'admin.card.index',
                'admin.card.add',
                'admin.card.details'
            )
                ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark'
                : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}"
                    onclick="toggleMenu('{{ $menuPrefix }}-card-menu', '{{ $menuPrefix }}-card-arrow-icon')">

        <span class="flex items-center">
            <i class="fa-light fa-wallet p-2"></i>
            @lang('master.user_card')
        </span>

                <i id="{{ $menuPrefix }}-card-arrow-icon" class="fa-light fa-plus"></i>
            </button>

            <div id="{{ $menuPrefix }}-card-menu"
                 class="@if(! request()->routeIs(
                    'admin.bank.index',
                    'admin.bank.add',
                    'admin.bank.details',
                    'admin.card.index',
                    'admin.card.add',
                    'admin.card.details'
                )) hidden @endif
                ml-6 space-y-1">

                @can('admin.bank.index')
                    <a href="{{ route('admin.bank.index') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.bank.index','admin.bank.add','admin.bank.details')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.banks')
                    </a>
                @endcan
                @can('admin.card.index')
                    <a href="{{ route('admin.card.index') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.user.card.index','admin.user.card.add','admin.user.card.details')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.cards')
                    </a>
                @endcan
            </div>
        </div>
    @endcanany
    <!-- Reports -->

    @canany([
    'admin.reports.transaction',
    'admin.reports.wallets',
    'admin.reports.user.assets',
    'admin.reports.profit',
    'admin.reports.profits',
    'admin.reports.spot.order',
    'admin.reports.future.order',
    'admin.reports.open.bank.logs',
    'admin.reports.converted.inventory',
    'admin.reports.currency.deposit.log',
    'admin.reports.deposit.request',
    'admin.reports.marketing.parents',
    'admin.reports.supplier.log'
])
        <div class="space-y-1">
            <button type="button"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg
        {{ request()->routeIs('admin.reports.*')
            ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark'
            : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}"
                    onclick="toggleMenu('{{ $menuPrefix }}-reports-menu', '{{ $menuPrefix }}-reports-arrow-icon')">

        <span class="flex items-center">
            <i class="fa-light fa-chart-line  p-2"></i>
            @lang('general.reports')
        </span>

                <i id="{{ $menuPrefix }}-reports-arrow-icon"
                   class="fa-light {{ request()->routeIs('admin.reports.*') ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>

            <div id="{{ $menuPrefix }}-reports-menu"
                 class="{{ request()->routeIs('admin.reports.*') ? '' : 'hidden' }} ml-6 space-y-1">

                {{-- Transactions --}}
                @can('admin.reports.transaction')
                    <a href="{{ route('admin.reports.transaction') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.transaction') && !request()->has('status')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.transactions')
                    </a>
                @endcan

                {{-- Wallets --}}
                @can('admin.reports.wallets')
                    <a href="{{ route('admin.reports.wallets') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.wallets')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.wallets')
                    </a>
                @endcan

                @can('admin.reports.user.assets')
                    <a href="{{ route('admin.reports.user.assets') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.user.assets')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.user_assets_summary')
                    </a>
                @endcan

                {{-- Profits --}}
                @can('admin.reports.profit')
                    <a href="{{ route('admin.reports.profit') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.profit','admin.reports.profits')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.profits')
                    </a>
                @endcan

                {{-- Spot Orders --}}
                @can('admin.reports.spot.order')
                    <a href="{{ route('admin.reports.spot.order') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.spot.order')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.spot_transaction')
                    </a>
                @endcan

                {{-- Future Orders --}}
                @can('admin.reports.future.order')
                    <a href="{{ route('admin.reports.future.order') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.future.order','admin.reports.future.order.detail')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.future_orders')
                    </a>
                @endcan

                {{-- Open Bank Logs --}}
                @can('admin.reports.open.bank.logs')
                    <a href="{{ route('admin.reports.open.bank.logs') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.open.bank.logs')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.open_bank_transactions')
                    </a>
                @endcan

                {{-- Converted Inventory --}}
                @can('admin.reports.converted.inventory')
                    <a href="{{ route('admin.reports.converted.inventory') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.converted.inventory')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.converted_inventory')
                    </a>
                @endcan

                {{-- Currency Deposit Logs --}}
                @can('admin.reports.currency.deposit.log')
                    <a href="{{ route('admin.reports.currency.deposit.log') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.currency.deposit.log*')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.currency_deposit_logs')
                    </a>
                @endcan

                {{-- Fishing Transactions --}}
                @can('admin.reports.transaction')
                    <a href="{{ route('admin.reports.transaction',['status'=>config('constants.transaction.fishing')]) }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.transaction')
                && request('status') == config('constants.transaction.fishing')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.fishing')
                    </a>
                @endcan

                {{-- Deposit Requests --}}
                @can('admin.reports.deposit.request')
                    <a href="{{ route('admin.reports.deposit.request') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.deposit.request')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.deposit_requests')
                    </a>
                @endcan

                {{-- marketing logs --}}
                @can('admin.reports.marketing.parents')
                    <a href="{{ route('admin.reports.marketing.parents') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.marketing.parents')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.marketing_logs')
                    </a>

                @endcan

                {{-- supplier logs --}}
                @can('admin.reports.supplier.log')
                    <a href="{{ route('admin.reports.supplier.log') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.reports.supplier.log')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.supplier_log')
                    </a>

                @endcan

            </div>
        </div>
    @endcanany

    {{-- Packages --}}
    @can('admin.packages.index')
        <a href="{{ route('admin.packages.index') }}"
           class="block px-4 py-3 text-sm font-medium rounded-lg
   {{ request()->routeIs('admin.packages.index','admin.packages.edit')
        ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark'
        : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
            <i class="fa-light fa-box-archive p-2"></i>
            @lang('master.packages')
        </a>
    @endcan


    {{-- Swap --}}
    @can('admin.swaps.index')
        <a href="{{ route('admin.swaps.index') }}"
           class="block px-4 py-3 text-sm font-medium rounded-lg
   {{ request()->routeIs('admin.swaps.index','admin.swaps.edit')
        ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark'
        : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
            <i class="fa-light fa-right-left p-2"></i>
            @lang('general.swap')
        </a>
    @endcan


    {{-- Staking --}}
    @can('admin.staking.index')
        <a href="{{ route('admin.staking.index') }}"
           class="block px-4 py-3 text-sm font-medium rounded-lg
   {{ request()->routeIs('admin.staking.index','admin.staking.detail')
        ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark'
        : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
            <i class="fa-light fa-layer-group p-2"></i>
            @lang('general.staking')
        </a>
    @endcan

    <!-- Tickets -->
    @canany([
     'admin.ticket.index',
     'admin.department.index'
 ])
        <div class="space-y-1">
            <button type="button"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg
        {{ request()->routeIs(
            'admin.ticket.*',
            'admin.department.*'
        ) ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark'
          : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}"
                    onclick="toggleMenu('{{ $menuPrefix }}-ticket-menu', '{{ $menuPrefix }}-ticket-arrow-icon')">

        <span class="flex items-center">
            <i class="fa-light fa-paper-plane p-2"></i>
            @lang('master.ticket')
        </span>

                <i id="{{ $menuPrefix }}-ticket-arrow-icon"
                   class="fa-light {{ request()->routeIs('admin.ticket.*','admin.department.*') ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>

            <div id="{{ $menuPrefix }}-ticket-menu"
                 class="{{ request()->routeIs('admin.ticket.*','admin.department.*') ? '' : 'hidden' }} ml-6 space-y-1">

                @can('admin.department.index')
                    <a href="{{ route('admin.department.index') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.department.*')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('master.department')
                    </a>
                @endcan

                @can('admin.ticket.index')
                    <a href="{{ route('admin.ticket.index') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.ticket.*')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('master.save_list')
                    </a>
                @endcan

            </div>
        </div>
    @endcanany

    <!-- Notifications -->
    @canany([
      'admin.notification.index',
      'admin.notification.tape.index'
  ])
        <div class="space-y-1">
            <button type="button"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg
        {{ request()->routeIs(
            'admin.notification.*'
        ) ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark'
          : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}"
                    onclick="toggleMenu('{{ $menuPrefix }}-notification-menu', '{{ $menuPrefix }}-notification-arrow-icon')">

        <span class="flex items-center">
            <i class="fa-light fa-bell p-2"></i>
            @lang('general.notifications')
        </span>

                <i id="{{ $menuPrefix }}-notification-arrow-icon"
                   class="fa-light {{ request()->routeIs('admin.notification.*') ? 'fa-minus' : 'fa-plus' }}"></i>
            </button>

            <div id="{{ $menuPrefix }}-notification-menu"
                 class="{{ request()->routeIs('admin.notification.*') ? '' : 'hidden' }} ml-6 space-y-1">

                @can('admin.notification.index')
                    <a href="{{ route('admin.notification.index',['section'=>config('constants.option.section_normal')]) }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.notification.index')
                && request('section') == config('constants.option.section_normal')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.normal_notification')
                    </a>

                    <a href="{{ route('admin.notification.index',['section'=>config('constants.option.section_pop_up')]) }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.notification.index')
                && request('section') == config('constants.option.section_pop_up')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.pop_up_notification')
                    </a>
                @endcan

                @can('admin.notification.tape.index')
                    <a href="{{ route('admin.notification.tape.index') }}"
                       class="block text-xs px-8 py-2 rounded
           {{ request()->routeIs('admin.notification.tape.*')
                ? 'text-primary dark:text-primary-dark'
                : 'text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
                        <i class="fa-light fa-chevron-left p-2"></i>
                        @lang('general.tape_notification')
                    </a>
                @endcan

            </div>
        </div>
    @endcanany
    <!-- Settings -->
    @can('admin.setting.index')
        <a href="{{ route('admin.setting.index') }}"
           class="block px-4 py-3 text-sm font-medium rounded-lg
   {{ request()->routeIs(
        'admin.setting.*',
        'admin.currency.*',
        'admin.network.*',
        'admin.wallet.repository.*',
        'admin.future.*',
        'admin.market.*',
        'admin.security.*',
    ) ? 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark'
      : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">
            <i class="fa-light fa-gear p-2"></i>
            @lang('master.setting')
        </a>
    @endcan
</nav>
