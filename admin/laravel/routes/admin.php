<?php

use App\Http\Controllers\Admin\AdminActivityLogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FutureController;
use App\Http\Controllers\Admin\IrtSettingsController;
use App\Http\Controllers\Admin\MarketController;
use App\Http\Controllers\Admin\MarketCurrencyController;
use App\Http\Controllers\Admin\MonitorController;
use App\Http\Controllers\Admin\NetworkController;
use App\Http\Controllers\Admin\NetworkProviderController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\PackagesController;
use App\Http\Controllers\Admin\ReportExportController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\StakingController;
use App\Http\Controllers\Admin\SwapsController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserCardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\WalletAddressListController;
use App\Http\Controllers\Admin\WalletRepositoryController;
use App\Models\User;
use App\Services\Api\V1\AuthService;
use Illuminate\Support\Facades\Route;

Route::adminAuthPrefix()->group(function () {
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::get('/captcha/reload', [AdminController::class, 'reloadCaptcha'])->name('captcha.reload');
    Route::post('login', [AdminController::class, 'login'])->name('admin.login.submit');
    Route::get('logout', [AdminController::class, 'logout'])->middleware('auth:admin')->name('admin.logout');
    Route::get('verify', [AdminController::class, 'showVerifyPage'])->name('verify');
    Route::post('verify', [AdminController::class, 'verify'])->name('verify.submit');
    Route::any('send/otp/{admin}', [AdminController::class, 'sendOtp'])->name('admin.otp.send');
    Route::get('admin-inactive', function () {
        return view('admin.errors.admin_inactive');
    })->name('admin.inactive');
});
//
//// Protected admin routes
Route::adminAuthPrefix()->middleware(['auth:admin', 'demo.limit', 'set.log_info', 'check.admin.permission', 'restrict.ip'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Admin Profile
    Route::prefix('profile')->name('admin.')->controller(AdminController::class)->group(function () {
        Route::get('', 'profile')->name('profile');
        Route::get('security', 'security')->name('security');
        Route::post('update/profile/{admin}', 'updateProfile')->name('profile.update');
        Route::post('update/password/{admin}', 'updatePassword')->name('password.update');
        Route::post('verify/2fa/{admin}', 'verify2FA')->name('2fa.verify');
        Route::post('toggle/2fa/{admin}', 'toggleGoogle2fa')->name('2fa.toggle');
        Route::post('toggle/otp/{admin}', 'toggleOtp')->name('otp.toggle');
    });
    // Admin Management Routes
    Route::prefix('admin')->name('admin.')->controller(AdminController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{admin}', 'edit')->name('edit');
        Route::post('update/{admin}', 'update')->name('update');
        Route::delete('destroy/{admin}', 'destroy')->name('destroy');
        Route::get('chat', 'chat')->name('chat');
        Route::post('chat', 'chatSend')->name('chat.send');
        Route::get('reset/service', 'resetServices')->name('reset.service');
    });

    Route::prefix('roles')->name('roles.')->controller(AdminRoleController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{role}', 'edit')->name('edit');
        Route::put('/{role}', 'update')->name('update');
    });

    Route::prefix('logs')->name('activity.log.')->controller(AdminActivityLogController::class)->group(function () {
        Route::get('index', [AdminActivityLogController::class, 'index'])->name('index');
        Route::get('export', [AdminActivityLogController::class, 'exportAdminLogs'])->name('export');
        Route::delete('destroy', [AdminActivityLogController::class, 'destroy'])->name('destroy');
    });

    // User Management Routes
    Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{user}', 'edit')->name('edit');
        Route::post('update/{user}', 'update')->name('update');
        // Route::delete('destroy/{user}', 'destroy')->name('destroy');
        Route::get('/toggle-status/{user}', 'toggleStatus')->name('toggle-status');
        Route::get('authentication', 'authentications')->name('authentication');
        Route::get('enter/panel/{user}', 'enterUserPanel')->name('enter.panel');
    });

    Route::prefix('user/details')->name('user.details.')->controller(UserController::class)->group(function () {
        Route::prefix('security')->group(function () {
            Route::get('/{user}', 'security')->name('security');
            Route::delete('destroy/google/auth/{auth}', 'destroyGoogleAuth')->name('security.destroy.google.auth');
            Route::delete('destroy/token/{token}', 'destroyToken')->name('security.destroy.token');
        });
        Route::get('authentication/{user}', 'authentication')->name('authentication');
        Route::get('authentication/{user}/update', 'authenticationUpdate')->name('authentication.update');
        Route::get('authentication/{user}/document-status', 'updateDocumentStatus')->name('document.status.update');
        Route::post('package/{user}/update', 'packageUpdate')->name('package.update');
        Route::get('identity-inquiry/{user}', 'identityInquiry')->name('identity.inquiry');
        Route::get('mobile-inquiry/{user}/', 'mobileInquiry')->name('mobile.inquiry');
        Route::get('storage/delete', 'deleteFromStorage')->name('storage.delete');
        Route::get('assets/{user}', 'assets')->name('assets');
        Route::post('assets/update/{asset}', 'assetUpdate')->name('assets.update');
        Route::get('asset/logs', 'changeAssets')->name('asset.logs');
        Route::get('assets/refresh/{user}', 'generateAssetByActivateCurrencies')->name('assets.refresh');
        Route::get('package/{user}', 'package')->name('package');
        Route::get('manual-transaction/{user}', 'manualTransaction')->name('manual.transaction');
        Route::post('manual-transaction/{user}', 'storeManualTransaction')->name('manual.transaction.store');
        Route::get('manual-transaction/show/{transaction}', 'manualTransactionShow')->name('manual.transaction.show');
    });

    Route::prefix('bank')->name('bank.')->controller(BankController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('store', 'store')->name('store');
        Route::get('/detail/{bank}', 'details')->name('details');
        Route::post('/update/{bank}', 'update')->name('update');
    });

    Route::prefix('card')->name('card.')->controller(UserCardController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('store', 'store')->name('store');
        Route::get('detail/{card}', 'details')->name('details');
        Route::post('update/{card}', 'update')->name('update');
        Route::delete('destroy/{card}', 'destroy')->name('destroy');
        Route::get('inquiry/{card}', 'cardInquiry')->name('inquiry');
    });

    // Reports Route
    Route::prefix('reports')->name('reports.')->controller(ReportsController::class)->group(function () {
        Route::prefix('transaction')->group(function () {
            Route::get('', 'transaction')->name('transaction');
            Route::get('edit/{transaction}', 'transactionEdit')->name('transaction.edit');
            Route::post('update/{transaction}', 'transactionUpdate')->name('transaction.update');
        });
        Route::get('wallets', 'wallets')->name('wallets');
        Route::get('user-assets', 'userAssetsSummary')->name('user.assets');
        Route::get('user-assets/{currency}', 'userAssetsDetail')->name('user.assets.detail');
        Route::get('profit', 'profit')->name('profit');
        Route::get('profits/{currency}', 'profits')->name('profits');
        Route::get('spot/order', 'spotOrder')->name('spot.order');
        Route::get('spot/order/detail/{order}', 'spotOrderDetails')->name('spot.order.detail');
        Route::post('spot/order/{order}/resend-supplier', 'spotOrderResendToSupplier')->name('spot.order.resend.supplier');
        Route::get('future/order', 'futureOrder')->name('future.order');
        Route::get('future/order/detail/{order}', 'futureOrderDetails')->name('future.order.detail');
        Route::prefix('open/bank')->group(function () {
            Route::get('log', 'openBankLogs')->name('open.bank.logs');
            Route::post('update', 'updateOpenBankLog')->name('open.bank.log.update');
        });

        Route::get('converted/inventory', 'convertedInventory')->name('converted.inventory');
        Route::get('fishing', 'fishing')->name('fishing');
        Route::get('logs', 'currencyDepositLogs')->name('currency.deposit.log');
        Route::get('logs/{log}', 'currencyDepositLogShow')->name('currency.deposit.log.show');
        Route::post('logs', 'currencyDepositLogsUpdate')->name('currency.deposit.log.update');
        Route::post('logs/{log}/approve', 'currencyDepositLogApprove')->name('currency.deposit.log.approve');
        Route::post('logs/{log}/reject', 'currencyDepositLogReject')->name('currency.deposit.log.reject');
        Route::get('deposit/request', 'depositRequest')->name('deposit.request');
        Route::prefix('marketing')->group(function () {
            Route::get('parents', 'marketingParents')->name('marketing.parents');
            Route::get('users', 'marketingUsers')->name('marketing.users');
            Route::get('logs', 'marketingLogs')->name('marketing.logs');
        });
        Route::get('supplier/log', 'supplierLog')->name('supplier.log');
    });
    Route::prefix('reports/excel')->name('reports.excel.')->controller(ReportExportController::class)->group(function () {
        Route::get('/user/transaction', 'transactionExcel')->name('transaction');
        Route::get('/user/profit', 'profitExcel')->name('profit');
        Route::get('/user/profits/{currencyId}', 'profitsExcel')->name('profits');
        Route::get('/user/spot', 'spotOrderExcel')->name('spot');
        Route::get('/user/wallet', 'walletRepositoryExcel')->name('wallet');
        Route::get('/user/future', 'futureOrderExcel')->name('future');
        Route::get('/user/swap', 'swapExcel')->name('swap');
        Route::get('/user/staking', 'stakingExcel')->name('staking');
        Route::get('/user/openbank', 'openBankLogsExcel')->name('openBank');
        Route::get('/user/convertedInventory', 'convertedInventoryExcel')->name('convertedInventory');
        Route::get('/user/currencyDeposit', 'currencyDepositExcel')->name('currencyDeposit');
        Route::get('/user/depositRequest', 'depositRequestExcel')->name('depositRequest');
        Route::get('/user/supplierLog', 'supplierLogExcel')->name('supplierLog');
        Route::get('/user/marketingLogs/{user}', 'marketingLogsExcel')->name('marketingLogs');
        Route::get('/user/marketingParents', 'marketingParentsExcel')->name('marketingParents');
        Route::get('/user/marketingUsers/{user}', 'marketingUsersExcel')->name('marketingUsers');
    });

    //user packages route
    Route::prefix('packages')->name('packages.')->controller(PackagesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit/{package}', 'edit')->name('edit');
        Route::post('/update/{package}', 'update')->name('update');
    });

    //swap routes
    Route::prefix('swaps')->name('swaps.')->controller(SwapsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('edit/{swap}', 'edit')->name('edit');
        Route::post('update/{swap}', 'update')->name('update');
    });

    Route::prefix('staking')->name('staking.')->controller(StakingController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('detail/{userStaking}', 'details')->name('detail');
        Route::get('cancel/{userStaking}', 'cancel')->name('cancel');
        Route::get('accept/{userStaking}', 'accept')->name('accept');
    });

    // Ticket Route
    Route::prefix('ticket')->name('ticket.')->controller(TicketController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/chat/{ticket}', 'chat')->name('chat');
        Route::post('/chat/create{ticket}', 'createChat')->name('chat.create');
        Route::delete('/chat/{message}', [TicketController::class, 'deleteMessage'])
            ->name('message.delete');
        Route::put('/chat/{message}', [TicketController::class, 'updateMessage'])
            ->name('message.update');
        Route::put('/update/status/{ticket}', 'updateStatus')->name('updateStatus');
        Route::get('close/{ticket}', 'close')->name('ticket.close');
    });

    // Department Routes
    Route::prefix('department')->name('department.')->controller(DepartmentController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{department}', 'edit')->name('edit');
        Route::post('update/{department}', 'update')->name('update');
        Route::delete('destroy/{department}', 'destroy')->name('destroy');
    });

    Route::prefix('notification')->name('notification.')->controller(NotificationsController::class)->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{notification}', 'edit')->name('edit');
        Route::delete('destroy/{notification}', 'destroy')->name('destroy');
        Route::post('update/{notification}', 'update')->name('update');
        Route::get('tape', 'tapeIndex')->name('tape.index');
        Route::get('tape/create', 'tapeCreate')->name('tape.create');
        Route::post('tape', 'tapeStore')->name('tape.store');
        Route::get('tape/edit', 'tapeEdit')->name('tape.edit');
        Route::post('tape/update', 'tapeUpdate')->name('tape.update');
        Route::delete('tape/destroy', 'tapeDestroy')->name('tape.destroy');
    });

    // Settings Route
    Route::prefix('setting')->name('setting.')->controller(SettingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('monitor', 'monitor')->name('monitor');
        Route::prefix('site')->name('site.')->controller(SiteController::class)->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('general', 'general')->name('general');
            Route::get('about_us', 'aboutUs')->name('aboutUs');
            Route::prefix('rule')->name('rule.')->group(function () {
                Route::get('', 'rules')->name('index');
                Route::get('link', 'ruleLinks')->name('link');
                Route::get('deposit', 'depositRule')->name('deposit');
                Route::get('withdraw', 'withdrawRule')->name('withdraw');
                Route::get('future', 'futureRule')->name('future');
            });
            Route::get('slide', 'slide')->name('slide');
        });
        Route::prefix('sms')->name('sms.')->group(function () {
            Route::get('', 'sms')->name('index');
            Route::get('logs', 'smsNotificationLogs')->name('logs');
            Route::get('setting', 'smsSetting')->name('setting');
            Route::get('user/pattern', 'smsUserPattern')->name('user.pattern');
            Route::get('admin/pattern', 'smsAdminPattern')->name('admin.pattern');
        });
        Route::prefix('email')->name('email.')->group(function () {
            Route::get('', 'email')->name('index');
            Route::get('logs', 'emailNotificationLogs')->name('logs');
            Route::get('setting', 'emailSetting')->name('setting');
            Route::get('user/pattern', 'emailUserPattern')->name('user.pattern');
            Route::get('admin/pattern', 'emailAdminPattern')->name('admin.pattern');
        });
        Route::get('/currency/management', 'currencyManagement')->name('currency.management');
        Route::prefix('wallet-address-list')->name('wallet-address-list.')->controller(WalletAddressListController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('{type}', 'show')->name('show')->whereIn('type', ['white', 'black']);
            Route::post('{type}', 'store')->name('store')->whereIn('type', ['white', 'black']);
            Route::post('{type}/import', 'import')->name('import')->whereIn('type', ['white', 'black']);
            Route::get('{type}/sample', 'sample')->name('sample')->whereIn('type', ['white', 'black']);
            Route::put('{walletAddressList}', 'update')->name('update');
            Route::delete('{walletAddressList}', 'destroy')->name('destroy');
        });
        Route::get('futures', 'futures')->name('futures');
        Route::post('futures/adl', 'storeFutureAdlSettings')->name('futures.adl.store');
        Route::get('supplier', 'supplier')->name('supplier');
        Route::post('supplier/store', 'supplierStore')->name('supplier.store');
        Route::post('supplier/api-key/test', 'supplierApiKeyTest')->name('supplier.api.key.test');
        Route::get('supplier/sync', 'supplierSync')->name('supplier.sync');
        Route::get('supplier/network', 'supplierNetwork')->name('supplier.network');
        Route::get('supplier/inventory', 'supplierInventory')->name('supplier.inventory');
        Route::get('supplier/assets', 'supplierAssets')->name('supplier.assets');
        Route::post('supplier/wallet/balance/{supplier}', 'supplierWalletBalance')->name('supplier.wallet.balance');
        Route::delete('supplier/asset/{supplierAsset}', 'supplierAssetDestroy')->name('supplier.asset.destroy');
        Route::get('auth', 'authSetting')->name('auth');
        Route::get('swap', 'swap')->name('swap.index');
        Route::post('swap', 'swapStore')->name('swap.store');
        Route::post('swap/{swap}/update', 'swapUpdate')->name('swap.update');
        Route::get('gates', 'gates')->name('gates');
        Route::get('converted-inventory', 'convertedInventory')->name('converted.inventory');
        Route::get('security', 'security')->name('security');
        Route::prefix('staking')->name('staking.')->group(function () {
            Route::get('', 'staking')->name('index');
            Route::post('save', 'saveStaking')->name('save');
            Route::get('detail/{staking}', 'detailsStaking')->name('details');
            Route::post('update/{staking}', 'updateStaking')->name('update');
        });
        Route::get('api_setting', 'apiSetting')->name('api.setting');
        Route::post('api_setting', 'apiSettingStore')->name('api.setting.store');
        Route::delete('api_setting/{id}', 'apiDestroy')->name('api.destroy');
        Route::get('api_domain/{id}', 'apiDomain')->name('api.domain');
        Route::post('api_domain/{id}', 'apiDomainStore')->name('api.domain.store');
        Route::delete('api_domain/{id}/{domain_id}', 'domainDestroy')->name('api.domain.destroy');
        Route::prefix('spot')->name('spot.')->group(function () {
            Route::get('', 'spot')->name('index');
        });
        Route::prefix('plugin')->name('plugin.')->group(function () {
            Route::get('', 'plugin')->name('index');
            Route::get('deposit_request', 'depositRequest')->name('deposit.request');
            Route::get('telegram_messenger', 'telegramMessenger')->name('telegram.messenger');
            Route::get('smsTo', 'smsToMessanger')->name('smsTo.messenger');
            Route::get('id-analyzer', 'idAnalyzerSettings')->name('idAnalyzer.settings');
            Route::get('aml-bot', 'amlBotSettings')->name('amlBot.settings');
            Route::get('bitgo', 'bitgo')->name('bitgo');
        });
        Route::get('chats', 'adminChats')->name('chats');
        Route::prefix('bonus')->name('bonus.')->group(function () {
            Route::get('', 'bonus')->name('index');
            Route::get('edit', 'bonusEdit')->name('edit');
            Route::post('update', 'bonusUpdate')->name('update');
            Route::get('logs', 'bonusLogs')->name('logs');
        });
        Route::get('backup', 'backup')->name('backup');
        Route::get('license', 'license')->name('license');
        Route::get('license/test', 'testLicense')->name('license.test');
        Route::post('license/update', 'updateLicense')->name('license.update');
        Route::post('backup/download', 'backupDownload')->name('backup.download');
    });

    Route::prefix('security')->name('security.')->controller(SecurityController::class)->group(function () {
        Route::get('google/captcha', 'googleCaptcha')->name('google.captcha');
        Route::get('login/url', 'adminUrl')->name('login.url');
        Route::get('ip', 'ip')->name('ip');
        Route::post('ip/store', 'storeIp')->name('ip.store');
        Route::delete('ip/destroy', 'ipDestroy')->name('ip.destroy');
    });

    // Option Route
    Route::prefix('option')->name('option.')->controller(OptionController::class)->group(function () {
        Route::post('/', 'save')->name('save');
        Route::post('/pattern', 'savePattern')->name('pattern.save');
    });

    Route::prefix('irt')->name('irt.')->controller(IrtSettingsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/status', 'status')->name('status');
        Route::get('/card', 'card')->name('card');
        Route::get('/gateway', 'gateway')->name('gateway');
        Route::post('card/store', 'cardStore')->name('card.store');
        Route::post('card/update/{card}', 'cardUpdate')->name('card.update');
        Route::delete('card/{card}', 'cardDestroy')->name('card.destroy');
        Route::get('usdt', 'usdtSetting')->name('usdt');
        Route::get('open-bank', 'openBank')->name('openBank');
        Route::post('open-bank', 'openBankUpdate')->name('openBank.update');
        Route::get('connector-gate', 'connectorGate')->name('connector.gate');
    });

    // Currency Route
    Route::prefix('currency')->name('currency.')->controller(CurrencyController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/inactive', 'inactive')->name('inactive');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{currency}', 'edit')->name('edit');
        Route::post('/update/{currency}', 'update')->name('update');
        Route::post('networking', 'networking')->name('networking');
        Route::post('change/price', 'changePrice')->name('change.price');
        Route::prefix('voucher')->name('voucher.')->controller(CurrencyController::class)->group(function () {
            Route::post('save/option{currency}', 'saveVoucherOption')->name('save.option');
            Route::get('update/balance{currency}', [VoucherController::class, 'updateBalance'])->name('update.balance');
        });
    });

    // Network Route
    Route::prefix('network')->name('network.')->controller(NetworkController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/guide', 'guide')->name('guide');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{network}', 'edit')->name('edit');
        Route::post('/update/{network}', 'update')->name('update');
        // Network Provider Route
        Route::prefix('provider')->name('provider.')->controller(NetworkProviderController::class)->group(function () {
            Route::post('/{network}', 'update')->name('update');
            Route::get('supplier', 'supplier')->name('supplier');
            Route::get('setting/{network}', 'setting')->name('setting');
            Route::post('setting/update/{network}', 'updateSetting')->name('setting.update');
        });
    });


    // Wallet Repository Route
    Route::prefix('wallet/repository')->name('wallet.repository.')->controller(WalletRepositoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update/{network}', 'update')->name('update');
    });
    // Futures Route
    Route::prefix('future')->name('future.')->controller(FutureController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update/fee', 'updateFee')->name('update.fee');
        Route::get('/download/config', 'downloadConfig')->name('download.config');
        Route::get('asset', 'asset')->name('asset');
        Route::post('asset/transaction', 'transaction')->name('asset.transaction');
        Route::get('market/currency', 'marketCurrency')->name('market.currency');
        Route::post('market/currency/create', 'storeMarketCurrencies')->name('market.currency.create');
        Route::post('market/currency/update/{marketCurrency}', 'updateMarketCurrencies')->name('market.currency.update');
        Route::get('market/currency/detail/{marketCurrency}', 'marketCurrencyDetails')->name('market.currency.detail');
        Route::get('market/currency/inactive/{marketCurrency}', 'inactiveMarketCurrency')->name('market.currency.inactive');
        Route::get('market/currency/active/{marketCurrency}', 'activeMarketCurrency')->name('market.currency.active');
        Route::get('bot', 'bot')->name('bot');
        Route::post('bot/creat', 'creatBot')->name('bot.creat');
    });

    // Market Route
    Route::prefix('market')->name('market.')->controller(MarketController::class)->group(function () {
        Route::get('currency', 'marketCurrency')->name('currency');
        Route::get('chart', 'chart')->name('chart');
        Route::get('currency/inactive/{marketCurrency}', 'marketCurrencyInactive')->name('currency.inactive');
        Route::get('currency/active/{marketCurrency}', 'marketCurrencyActive')->name('currency.status.active');
    });
    // Market Currency Route
    Route::prefix('market/currency')->name('market.currency.')->controller(MarketCurrencyController::class)->group(function () {
        Route::post('create', 'create')->name('create');
        Route::get('list/{currency}', 'lists')->name('list');
        Route::get('bot/{currency}', 'bot')->name('bot');
        Route::get('taker/bot/{currency}', 'takerBot')->name('bot.taker');
        Route::post('bot/creat/{currency}', 'creatBot')->name('bot.creat');
        Route::post('order/bot/creat/{currency}', 'creatOrderBot')->name('bot.order.creat');
        Route::get('details/{currency}', 'details')->name('details');
        Route::post('update/{marketCurrency}', 'update')->name('update');
        Route::post('detail/update/{marketCurrency}', 'updateDetail')->name('detail.update');
    });

    Route::prefix('monitor')->name('monitor.')->controller(MonitorController::class)->group(function () {
        Route::get('price/socket', [MonitorController::class, 'currencyPriceSocket'])->name('price.socket');
        Route::get('future/order/book/socket', [MonitorController::class, 'futureOrderSocket'])->name('future.order.book.socket');
        Route::get('future/open/order/socket', [MonitorController::class, 'futureOpenOrderSocket'])->name('future.open.order.socket');
        Route::get('system/logs', [MonitorController::class, 'systemLogs'])->name('system.log');
        Route::delete('system/logs/delete', [MonitorController::class, 'deleteSystemLogs'])->name('system.log.delete');
    });
    Route::post('upload-image', [NotificationsController::class, 'uploadImage'])->name('upload.image');
});
