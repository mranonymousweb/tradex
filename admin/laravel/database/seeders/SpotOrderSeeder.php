<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Market;
use App\Models\MarketCurrency;
use App\Models\MarketHistory;
use App\Models\UserTransaction;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;

class SpotOrderSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $btc = $this->requireCurrency(Currency::SYMBOL_BTC);
        $eth = $this->requireCurrency(Currency::SYMBOL_ETH);
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);

        $this->ensureMarketPair($btc, $usdt, MarketCurrency::TYPE_SPOT);
        $this->ensureMarketPair($eth, $usdt, MarketCurrency::TYPE_SPOT);

        $traders = $this->demoTraders();
        $this->creditDemoAssets($traders, [
            Currency::SYMBOL_USDT => [50000],
            Currency::SYMBOL_BTC => [2],
            Currency::SYMBOL_ETH => [10],
        ]);

        $this->wipePrevious();

        $pairs = [
            ['main' => $btc, 'base' => $usdt, 'price' => 65000],
            ['main' => $eth, 'base' => $usdt, 'price' => 3500],
        ];

        $created = 0;
        while ($created < self::DEMO_RECORD_COUNT) {
            $pair = $pairs[$created % 2];
            $buyer = $traders[$created % count($traders)];
            $seller = $traders[($created + 1) % count($traders)];
            $bucket = $created % 10;
            $openedAt = now()->subHours(($created % 72) + 1);

            if ($bucket < 6 && $created + 1 < self::DEMO_RECORD_COUNT) {
                $amount = round(0.01 + ($created % 20) * 0.005, 6);
                $buy = $this->createOrder($buyer, $pair['main'], $pair['base'], [
                    'side' => Market::SIDE_BUY,
                    'type' => Market::TYPE_LIMIT,
                    'amount' => $amount,
                    'price' => $pair['price'],
                    'status' => Market::STATUS_DONE,
                    'open_at' => $openedAt,
                    'done_at' => $openedAt->copy()->addMinutes(12),
                ]);
                $sell = $this->createOrder($seller, $pair['main'], $pair['base'], [
                    'side' => Market::SIDE_SELL,
                    'type' => Market::TYPE_LIMIT,
                    'amount' => $amount,
                    'price' => $pair['price'],
                    'status' => Market::STATUS_DONE,
                    'open_at' => $openedAt,
                    'done_at' => $openedAt->copy()->addMinutes(12),
                ]);
                MarketHistory::create([
                    'market_id' => $buy->id,
                    'sub_market_id' => $sell->id,
                    'amount' => $amount,
                    'price' => $pair['price'],
                    'global_price' => $amount * $pair['price'],
                    'is_robot' => false,
                    'meta' => [
                        'Maker' => "Order #{$buy->id}",
                        'Taker' => "Order #{$sell->id}",
                    ],
                    'operation' => [
                        'maker' => ['id' => $buy->id, 'fee' => (float) $buy->fee],
                        'taker' => ['id' => $sell->id, 'fee' => (float) $sell->fee],
                    ],
                ]);
                $created += 2;
                continue;
            }

            $statusMap = [
                6 => ['status' => Market::STATUS_OPEN, 'filled' => false],
                7 => ['status' => Market::STATUS_CANCELED, 'filled' => false, 'canceled_at' => $openedAt->copy()->addHour()],
                8 => ['status' => Market::STATUS_IN_PROGRESS, 'filled_ratio' => 0.45],
                9 => ['status' => Market::STATUS_DONE],
            ];
            $extra = $statusMap[$bucket];
            $order = $this->createOrder($buyer, $pair['main'], $pair['base'], array_merge([
                'side' => $created % 2 ? Market::SIDE_BUY : Market::SIDE_SELL,
                'type' => $created % 3 ? Market::TYPE_LIMIT : Market::TYPE_MARKET,
                'amount' => round(0.02 + ($created % 15) * 0.01, 6),
                'price' => $pair['price'] * (1 + (($created % 7) - 3) * 0.004),
                'open_at' => $openedAt,
                'done_at' => ($extra['status'] ?? null) === Market::STATUS_DONE ? $openedAt->copy()->addMinutes(8) : null,
            ], $extra));

            if ($bucket === 9) {
                $order->update([
                    'supplier_request' => [
                        'from_currency_symbol' => $pair['base']->symbol,
                        'to_currency_symbol' => $pair['main']->symbol,
                        'from_currency_amount' => (float) $order->total,
                        'to_currency_amount' => (float) $order->amount,
                        'side' => 'buy',
                        'supplier' => 'gate_io',
                    ],
                    'supplier_response' => [
                        'status' => 1,
                        'id' => 'SEED-SPOT-' . $order->id,
                        'buy_amount' => (float) $order->amount,
                    ],
                ]);
            }

            $created++;
        }

        $this->command?->info('Spot sample orders: ' . Market::whereIn('user_id', $this->demoTraderIds())->count());
    }

    private function wipePrevious(): void
    {
        $ids = $this->demoTraderIds();
        if ($ids->isEmpty()) {
            return;
        }

        UserTransaction::whereIn('user_id', $ids)
            ->where('section', UserTransaction::SECTION_MARKET)
            ->delete();

        $marketIds = Market::whereIn('user_id', $ids)->pluck('id');
        if ($marketIds->isNotEmpty()) {
            MarketHistory::whereIn('market_id', $marketIds)
                ->orWhereIn('sub_market_id', $marketIds)
                ->delete();
        }

        Market::whereIn('user_id', $ids)->delete();
    }

    private function createOrder($user, Currency $main, Currency $base, array $data): Market
    {
        $amount = $data['amount'];
        $price = $data['price'];
        $total = $amount * $price;
        $feePercent = 0.001;
        $fee = $total * $feePercent;
        $filled = ($data['filled'] ?? true) !== false;
        $ratio = $data['filled_ratio'] ?? ($filled ? 1 : 0);
        $filledAmount = $amount * $ratio;
        $filledTotal = $total * $ratio;

        return Market::create([
            'user_id' => $user->id,
            'main_currency_id' => $main->id,
            'base_currency_id' => $base->id,
            'main_currency_symbol' => $main->symbol,
            'base_currency_symbol' => $base->symbol,
            'pairs' => "{$main->symbol}_{$base->symbol}",
            'side' => $data['side'],
            'type' => $data['type'],
            'leverage' => 1,
            'amount' => $amount,
            'price' => $price,
            'avg_price' => $filledAmount > 0 ? $price : 0,
            'total' => $total,
            'stop_price' => 0,
            'market_price' => $price,
            'filled_amount' => $filledAmount,
            'filled_receive_amount' => $data['side'] === Market::SIDE_BUY
                ? max($filledAmount - ($fee * $ratio / max($price, 1)), 0)
                : max($filledTotal - ($fee * $ratio), 0),
            'filled_total' => $filledTotal,
            'fee' => $fee * $ratio,
            'fee_percent' => $feePercent * 100,
            'status' => $data['status'],
            'meta' => ['seeded' => true],
            'open_at' => $data['open_at'] ?? now(),
            'done_at' => $data['done_at'] ?? null,
            'canceled_at' => $data['canceled_at'] ?? null,
        ]);
    }
}
