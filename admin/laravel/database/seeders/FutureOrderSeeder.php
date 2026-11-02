<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\FeatureHistory;
use App\Models\FeatureOrder;
use App\Models\MarketCurrency;
use App\Models\UserTransaction;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;

class FutureOrderSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $btc = $this->requireCurrency(Currency::SYMBOL_BTC);
        $eth = $this->requireCurrency(Currency::SYMBOL_ETH);
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);

        $this->ensureMarketPair($btc, $usdt, MarketCurrency::TYPE_FEATURE);
        $this->ensureMarketPair($eth, $usdt, MarketCurrency::TYPE_FEATURE);

        $traders = $this->demoTraders();
        $this->creditDemoAssets($traders, [
            Currency::SYMBOL_USDT => [50000, 8000],
        ]);

        $this->wipePrevious();

        $pairs = [
            ['main' => $btc, 'base' => $usdt, 'price' => 64500],
            ['main' => $eth, 'base' => $usdt, 'price' => 3480],
        ];

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $user = $traders[$i % count($traders)];
            $pair = $pairs[$i % 2];
            $openedAt = now()->subHours(($i % 96) + 1);
            $bucket = $i % 5;
            $side = $i % 2 ? FeatureOrder::SIDE_LONG : FeatureOrder::SIDE_SHORT;
            $leverage = [10, 15, 20, 25, 50][$i % 5];
            $quantity = $pair['main']->symbol === Currency::SYMBOL_BTC
                ? round(0.01 + ($i % 10) * 0.002, 6)
                : round(0.2 + ($i % 10) * 0.05, 6);
            $enterPrice = $pair['price'] * (1 + (($i % 9) - 4) * 0.003);

            $data = [
                'side' => $side,
                'type' => $i % 3 ? FeatureOrder::TYPE_MARKET : FeatureOrder::TYPE_LIMIT,
                'quantity' => $quantity,
                'enter_price' => $enterPrice,
                'leverage' => $leverage,
                'margin_mode' => $i % 4 ? FeatureOrder::MARGIN_MODE_CROSS : FeatureOrder::MARGIN_MODE_ISOLATED,
            ];

            if ($bucket === 0) {
                $data += [
                    'status' => FeatureOrder::STATUS_CREATED,
                    'opened_at' => $openedAt,
                    'has_tpsl' => true,
                    'take_profit' => $enterPrice * ($side === FeatureOrder::SIDE_LONG ? 1.04 : 0.96),
                    'stop_loss' => $enterPrice * ($side === FeatureOrder::SIDE_LONG ? 0.97 : 1.03),
                ];
            } elseif ($bucket === 1) {
                $data += [
                    'status' => FeatureOrder::STATUS_PENDING,
                    'type' => FeatureOrder::TYPE_LIMIT,
                    'opened_at' => null,
                    'price' => $enterPrice,
                ];
            } elseif ($bucket === 2) {
                $exit = $enterPrice * ($side === FeatureOrder::SIDE_LONG ? 1.02 : 0.98);
                $data += [
                    'status' => FeatureOrder::STATUS_CLOSED,
                    'opened_at' => $openedAt,
                    'closed_at' => $openedAt->copy()->addHours(6),
                    'exit_type' => FeatureOrder::EXIT_TYPE_TP,
                    'exit_price' => $exit,
                    'pnl' => abs($exit - $enterPrice) * $quantity * $leverage,
                ];
            } elseif ($bucket === 3) {
                $exit = $enterPrice * ($side === FeatureOrder::SIDE_LONG ? 0.97 : 1.03);
                $data += [
                    'status' => FeatureOrder::STATUS_CLOSED,
                    'opened_at' => $openedAt,
                    'closed_at' => $openedAt->copy()->addHours(3),
                    'exit_type' => FeatureOrder::EXIT_TYPE_SL,
                    'exit_price' => $exit,
                    'pnl' => -abs($exit - $enterPrice) * $quantity * $leverage,
                ];
            } else {
                $exit = $enterPrice * ($side === FeatureOrder::SIDE_LONG ? 0.94 : 1.06);
                $data += [
                    'status' => FeatureOrder::STATUS_CLOSED,
                    'opened_at' => $openedAt,
                    'closed_at' => $openedAt->copy()->addHours(2),
                    'liquidation_at' => $openedAt->copy()->addHours(2),
                    'exit_type' => FeatureOrder::EXIT_TYPE_LIGUID,
                    'exit_price' => $exit,
                    'pnl' => -($enterPrice * $quantity / max($leverage, 1)),
                ];
            }

            $order = $this->createOrder($user, $pair['main'], $pair['base'], $data);
            $order->histories()->create([
                'fee' => $order->fee,
                'type' => FeatureHistory::TYPE_MAIN,
                'description' => 'seeded-demo-trade',
            ]);
            if ($bucket >= 2) {
                $order->histories()->create([
                    'fee' => round((float) $order->fee * 0.2, 8),
                    'type' => FeatureHistory::TYPE_PERIOD,
                    'description' => 'seeded-demo-trade',
                ]);
            }
        }

        $this->command?->info('Futures sample orders: ' . FeatureOrder::whereIn('user_id', $this->demoTraderIds())->count());
    }

    private function wipePrevious(): void
    {
        $ids = $this->demoTraderIds();
        if ($ids->isEmpty()) {
            return;
        }

        UserTransaction::whereIn('user_id', $ids)
            ->where('section', UserTransaction::SECTION_FUTURE)
            ->delete();

        FeatureOrder::whereIn('user_id', $ids)->delete();
    }

    private function createOrder($user, Currency $main, Currency $base, array $data): FeatureOrder
    {
        $enterPrice = $data['enter_price'];
        $quantity = $data['quantity'];
        $leverage = $data['leverage'];
        $cost = $enterPrice * $quantity;
        $fee = $cost * 0.0004;
        $liqPrice = $data['side'] === FeatureOrder::SIDE_LONG
            ? $enterPrice * (1 - (1 / max($leverage, 1)))
            : $enterPrice * (1 + (1 / max($leverage, 1)));

        return FeatureOrder::create([
            'user_id' => $user->id,
            'main_currency_id' => $main->id,
            'base_currency_id' => $base->id,
            'main_currency_symbol' => $main->symbol,
            'base_currency_symbol' => $base->symbol,
            'pairs' => "{$main->symbol}_{$base->symbol}",
            'is_robot' => false,
            'has_tpsl' => $data['has_tpsl'] ?? false,
            'take_profit' => $data['take_profit'] ?? null,
            'stop_loss' => $data['stop_loss'] ?? null,
            'enter_balance' => $cost / max($leverage, 1),
            'quantity' => $quantity,
            'price' => $data['price'] ?? $enterPrice,
            'enter_price' => $enterPrice,
            'stop_price' => $data['stop_price'] ?? null,
            'exit_type' => $data['exit_type'] ?? null,
            'exit_price' => $data['exit_price'] ?? null,
            'liq_price' => abs($liqPrice),
            'fee' => $fee,
            'cost' => $cost,
            'pnl' => $data['pnl'] ?? null,
            'side' => $data['side'],
            'type' => $data['type'],
            'leverage' => $leverage,
            'margin_mode' => $data['margin_mode'] ?? FeatureOrder::MARGIN_MODE_CROSS,
            'opened_at' => $data['opened_at'] ?? now(),
            'closed_at' => $data['closed_at'] ?? null,
            'liquidation_at' => $data['liquidation_at'] ?? null,
            'status' => $data['status'],
        ]);
    }
}
