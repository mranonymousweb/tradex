<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\Department;
use App\Models\Network;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Models\UserAsset;
use App\Models\UserCard;
use App\Models\UserTransaction;
use App\Models\UserWallet;
use App\Models\WalletRepository;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;
use RuntimeException;

class DemoAccountSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $users = $this->demoTraders();
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);
        $irt = $this->requireCurrency(Currency::SYMBOL_IRT);
        $btc = $this->requireCurrency(Currency::SYMBOL_BTC);

        $this->creditDemoAssets($users, [
            Currency::SYMBOL_USDT => [50000, 8000],
            Currency::SYMBOL_BTC => [2],
            Currency::SYMBOL_ETH => [8],
            Currency::SYMBOL_IRT => [250000000],
        ]);

        $this->wipePrevious($users);

        $this->seedCards($users, $irt);
        $wallets = $this->seedWallets($users, $usdt, $btc);
        $this->seedTransactions($users, $usdt, $irt, $wallets);
        $this->seedTickets($users);
        $this->seedNotifications($users);

        $this->command?->info('Demo users/cards/wallets/transactions/tickets/notifications seeded.');
    }

    private function wipePrevious(array $users): void
    {
        $ids = collect($users)->pluck('id');
        if ($ids->isEmpty()) {
            return;
        }

        UserTransaction::whereIn('user_id', $ids)
            ->whereIn('section', [
                UserTransaction::SECTION_WALLET,
                UserTransaction::SECTION_GATE,
                UserTransaction::SECTION_CARD_TO_CARD,
            ])
            ->delete();

        Ticket::whereIn('user_id', $ids)->delete();
        Notification::whereIn('user_id', $ids)->delete();
        UserCard::whereIn('user_id', $ids)->delete();
        UserWallet::whereIn('user_id', $ids)->delete();
    }

    private function seedCards(array $users, Currency $irt): void
    {
        $bank = Bank::query()->first();
        if (!$bank) {
            throw new RuntimeException('No bank found. Run BankSeeder first.');
        }

        $statuses = [
            UserCard::STATUS_ACTIVE,
            UserCard::STATUS_WAITING,
            UserCard::STATUS_REJECTED,
            UserCard::STATUS_INACTIVE,
        ];

        foreach ($users as $index => $user) {
            UserCard::create([
                'user_id' => $user->id,
                'bank_id' => $bank->id,
                'currency_id' => $irt->id,
                'card_owner' => $user->getFullName(),
                'card_number' => sprintf('603799%08d', 10000000 + $user->id),
                'iban' => sprintf('IR%024d', 1000000000000000000 + $user->id),
                'account_number' => (string) (2200000000 + $user->id),
                'status' => $statuses[$index % count($statuses)],
                'description' => 'seeded-demo-card',
            ]);
        }
    }

    /**
     * @return array<int, UserWallet>
     */
    private function seedWallets(array $users, Currency $usdt, Currency $btc): array
    {
        $network = Network::query()
            ->whereIn('symbol', ['trc20', 'TRC20', 'TRX', 'trx'])
            ->first()
            ?? Network::query()->first();

        if (!$network) {
            throw new RuntimeException('No network found. Run NetworkSeeder first.');
        }

        $repo = WalletRepository::updateOrCreate(
            ['address' => 'TSeedDemoSharedUsdtAddress000001'],
            [
                'network_id' => $network->id,
                'symbol' => $network->symbol,
                'type' => WalletRepository::TYPE_SHARE,
                'is_used' => true,
                'status' => WalletRepository::STATUS_ACTIVE,
                'supplier' => WalletRepository::SUPPLIER_MANUAL,
            ]
        );

        $wallets = [];
        foreach ($users as $user) {
            $wallets[$user->id] = UserWallet::create([
                'wallet_id' => $repo->id,
                'currency_id' => $usdt->id,
                'network_id' => $network->id,
                'user_id' => $user->id,
                'symbol' => $usdt->symbol,
                'address' => $repo->address,
                'tag' => 'seed-' . $user->id,
                'supplier' => WalletRepository::SUPPLIER_MANUAL,
            ]);

            UserWallet::create([
                'wallet_id' => $repo->id,
                'currency_id' => $btc->id,
                'network_id' => $network->id,
                'user_id' => $user->id,
                'symbol' => $btc->symbol,
                'address' => 'bc1qseeddemo' . str_pad((string) $user->id, 20, '0'),
                'tag' => null,
                'supplier' => WalletRepository::SUPPLIER_MANUAL,
            ]);
        }

        return $wallets;
    }

    private function seedTransactions(array $users, Currency $usdt, Currency $irt, array $wallets): void
    {
        $bank = Bank::query()->first();
        $card = UserCard::whereIn('user_id', collect($users)->pluck('id'))->first();

        foreach ($users as $index => $user) {
            $usdtAsset = UserAsset::where('user_id', $user->id)->where('currency_id', $usdt->id)->first();
            $irtAsset = UserAsset::where('user_id', $user->id)->where('currency_id', $irt->id)->first();
            $wallet = $wallets[$user->id] ?? null;

            UserTransaction::create([
                'wallet_id' => $wallet?->id,
                'asset_id' => $usdtAsset?->id,
                'user_id' => $user->id,
                'currency_id' => $usdt->id,
                'network_id' => $wallet?->network_id,
                'amount' => 250 + $index * 10,
                'final_amount' => 250 + $index * 10,
                'fee' => 1,
                'txt_id' => 'SEED-TX-WALLET-' . $user->id,
                'section' => UserTransaction::SECTION_WALLET,
                'type' => UserTransaction::TYPE_DEPOSIT,
                'status' => UserTransaction::STATUS_CONFIRMED,
                'description' => 'seeded-demo-wallet-deposit',
                'address' => $wallet?->address,
                'old_balance' => 0,
                'new_balance' => $usdtAsset?->balance,
            ]);

            UserTransaction::create([
                'asset_id' => $irtAsset?->id,
                'user_id' => $user->id,
                'currency_id' => $irt->id,
                'bank_id' => $bank?->id,
                'card_id' => $card?->id,
                'amount' => 5000000 + $index * 100000,
                'final_amount' => 5000000 + $index * 100000,
                'fee' => 0,
                'txt_id' => 'SEED-TX-IRT-' . $user->id,
                'section' => UserTransaction::SECTION_GATE,
                'type' => UserTransaction::TYPE_DEPOSIT,
                'status' => $index % 3 ? UserTransaction::STATUS_CONFIRMED : UserTransaction::STATUS_WAITING,
                'gateway' => UserTransaction::ZARINPAL_GATEWAY,
                'description' => 'seeded-demo-irt-deposit',
            ]);

            if ($index === 0) {
                UserTransaction::create([
                    'wallet_id' => $wallet?->id,
                    'asset_id' => $usdtAsset?->id,
                    'user_id' => $user->id,
                    'currency_id' => $usdt->id,
                    'network_id' => $wallet?->network_id,
                    'amount' => 50,
                    'final_amount' => 49,
                    'fee' => 1,
                    'txt_id' => 'SEED-TX-WITHDRAW-' . $user->id,
                    'section' => UserTransaction::SECTION_WALLET,
                    'type' => UserTransaction::TYPE_WITHDRAW,
                    'status' => UserTransaction::STATUS_WAITING,
                    'description' => 'seeded-demo-wallet-withdraw',
                    'address' => 'TSeedExternalWithdrawAddress0001',
                ]);
            }
        }
    }

    private function seedTickets(array $users): void
    {
        $department = Department::query()->first();
        $admin = Admin::query()->first();
        $user = $users[0];

        $open = Ticket::create([
            'user_id' => $user->id,
            'admin_id' => $admin?->id,
            'title' => 'Seeded ticket: deposit delay',
            'description' => 'I deposited USDT but balance is not updated.',
            'status' => Ticket::STATUS_NEW,
            'department_id' => $department?->id,
            'from' => Ticket::FROM_USER,
            'priority' => Ticket::PRIORITY_HIGH,
        ]);

        TicketMessage::create([
            'ticket_id' => $open->id,
            'user_id' => $user->id,
            'user_type' => User::class,
            'message' => 'Please check txid SEED-TX-WALLET-' . $user->id,
            'status' => TicketMessage::STATUS_NOT_SEEN,
            'from' => TicketMessage::FROM_USER,
        ]);

        if (isset($users[1], $admin)) {
            $answered = Ticket::create([
                'user_id' => $users[1]->id,
                'admin_id' => $admin->id,
                'title' => 'Seeded ticket: KYC question',
                'description' => 'How long does KYC take?',
                'status' => Ticket::STATUS_ANSWERED,
                'department_id' => $department?->id,
                'from' => Ticket::FROM_ADMIN,
                'priority' => Ticket::PRIORITY_MEDIUM,
                'seen_at' => now(),
            ]);

            TicketMessage::create([
                'ticket_id' => $answered->id,
                'user_id' => $users[1]->id,
                'user_type' => User::class,
                'message' => 'How long does KYC take?',
                'status' => TicketMessage::STATUS_SEEN,
                'from' => TicketMessage::FROM_USER,
                'seen_at' => now(),
            ]);

            TicketMessage::create([
                'ticket_id' => $answered->id,
                'user_id' => $admin->id,
                'user_type' => Admin::class,
                'message' => 'Usually less than 24 hours.',
                'status' => TicketMessage::STATUS_NOT_SEEN,
                'from' => TicketMessage::FROM_ADMIN,
            ]);
        }
    }

    private function seedNotifications(array $users): void
    {
        $admin = Admin::query()->first();
        $user = $users[0];

        Notification::create([
            'user_id' => $user->id,
            'admin_id' => $admin?->id,
            'title' => 'Seeded notification',
            'description' => 'Your demo deposit was confirmed.',
            'status' => Notification::STATUS_ACTIVE,
            'type' => [Notification::TYPE_INTERNAl],
            'section' => Notification::SECTION_NORMAL,
            'read' => false,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'admin_id' => $admin?->id,
            'title' => 'Seeded maintenance notice',
            'description' => 'Scheduled maintenance tonight.',
            'status' => Notification::STATUS_ACTIVE,
            'type' => [Notification::TYPE_INTERNAl, Notification::TYPE_EMAIL],
            'section' => Notification::SECTION_TAPE,
            'read' => true,
            'read_at' => now(),
        ]);
    }
}
