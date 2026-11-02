<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Option;
use App\Models\Swap;
use App\Models\User;
use App\Models\UserTransaction;
use Database\Seeders\Concerns\SeedsDemoTradeData;
use Illuminate\Database\Seeder;
use RuntimeException;

class SwapSeeder extends Seeder
{
    use SeedsDemoTradeData;

    public function run(): void
    {
        $btc = $this->requireCurrency(Currency::SYMBOL_BTC);
        $eth = $this->requireCurrency(Currency::SYMBOL_ETH);
        $usdt = $this->requireCurrency(Currency::SYMBOL_USDT);
        $irt = $this->requireCurrency(Currency::SYMBOL_IRT);

        $traders = $this->demoTraders();
        $this->creditDemoAssets($traders, [
            Currency::SYMBOL_USDT => [50000],
            Currency::SYMBOL_BTC => [2],
            Currency::SYMBOL_ETH => [8],
            Currency::SYMBOL_IRT => [250000000],
        ]);

        $this->wipePrevious();

        $cryptoRoutes = [
            ['from' => $usdt, 'to' => $btc, 'from_price' => 1.0, 'to_price' => 65000.0, 'from_amount' => 650.0],
            ['from' => $btc, 'to' => $usdt, 'from_price' => 65000.0, 'to_price' => 1.0, 'from_amount' => 0.02],
            ['from' => $usdt, 'to' => $eth, 'from_price' => 1.0, 'to_price' => 3500.0, 'from_amount' => 700.0],
            ['from' => $eth, 'to' => $usdt, 'from_price' => 3500.0, 'to_price' => 1.0, 'from_amount' => 0.3],
        ];

        // Keep IRT prices decimal-friendly for toCurrency() / detail view
        $irtRoutes = [
            ['from' => $usdt, 'to' => $irt, 'from_price' => 1.0, 'to_price' => 0.00001, 'from_amount' => 100.0],
            ['from' => $irt, 'to' => $usdt, 'from_price' => 0.00001, 'to_price' => 1.0, 'from_amount' => 10000000.0],
        ];

        $cryptoStatuses = [
            Swap::STATUS_WAITING,
            Swap::STATUS_WAITING_BY_SUPPLIER,
            Swap::STATUS_ACCEPTED_BY_SITE,
            Swap::STATUS_ACCEPTED_BY_SUPPLIER,
            Swap::STATUS_REJECTED_BY_SITE,
            Swap::STATUS_REJECTED_BY_SUPPLIER,
        ];

        $irtStatuses = [
            Swap::STATUS_WAITING,
            Swap::STATUS_ACCEPTED_BY_SITE,
            Swap::STATUS_REJECTED_BY_SITE,
        ];

        $createdIds = [];

        for ($i = 0; $i < self::DEMO_RECORD_COUNT; $i++) {
            $user = $traders[$i % count($traders)];
            $useIrt = ($i % 5) === 4;
            $route = $useIrt
                ? $irtRoutes[$i % count($irtRoutes)]
                : $cryptoRoutes[$i % count($cryptoRoutes)];
            $status = $useIrt
                ? $irtStatuses[$i % count($irtStatuses)]
                : $cryptoStatuses[$i % count($cryptoStatuses)];

            $swap = $this->createSwap($user, $route['from'], $route['to'], [
                'index' => $i + 1,
                'status' => $status,
                'from_amount' => $route['from_amount'] * (1 + ($i % 5) * 0.1),
                'from_price' => $route['from_price'],
                'to_price' => $route['to_price'],
                'fee_percent' => $useIrt ? 0.002 : 0.004,
                'swap_metod' => $useIrt ? Option::SWAP_METHOD_INNER : Option::SWAP_METHOD_INTERNATIONAL,
            ]);

            $createdIds[] = $swap->id;
        }

        $this->assertCompatibleWithAdminViews($createdIds);

        $this->command?->info('Swap sample records: ' . count($createdIds));
    }

    private function wipePrevious(): void
    {
        $ids = $this->demoTraderIds();
        if ($ids->isEmpty()) {
            return;
        }

        UserTransaction::whereIn('user_id', $ids)
            ->where('section', UserTransaction::SECTION_SWAP)
            ->delete();

        Swap::whereIn('user_id', $ids)
            ->where(function ($q) {
                $q->where('txt_id', 'like', 'SEED-SWAP-%')
                    ->orWhere('description', 'like', '%seeded-demo%')
                    ->orWhere('description', 'like', '%Rejected (seeded demo)%');
            })
            ->delete();
    }

    private function createSwap(User $user, Currency $from, Currency $to, array $data): Swap
    {
        $fromAmount = (float) $data['from_amount'];
        $fromPrice = (float) $data['from_price'];
        $toPrice = (float) $data['to_price'];
        $toAmount = $toPrice > 0 ? ($fromAmount * $fromPrice) / $toPrice : 0.0;
        $feePercent = (float) ($data['fee_percent'] ?? 0.004);
        $fee = $toAmount * $feePercent;
        $toFinal = $toAmount - $fee;
        $status = $data['status'];
        $index = (int) $data['index'];
        $isIrt = $from->symbol === Currency::SYMBOL_IRT || $to->symbol === Currency::SYMBOL_IRT;

        if ($isIrt) {
            if ($from->symbol === Currency::SYMBOL_IRT) {
                $fromAmount = floor($fromAmount);
            }
            if ($to->symbol === Currency::SYMBOL_IRT) {
                $toAmount = floor($toAmount);
                $toFinal = floor($toFinal);
                $fee = floor($fee);
            }
        }

        $supplierFlow = in_array($status, [
            Swap::STATUS_WAITING_BY_SUPPLIER,
            Swap::STATUS_ACCEPTED_BY_SUPPLIER,
            Swap::STATUS_REJECTED_BY_SUPPLIER,
        ], true) && !$isIrt;

        $acceptedBySupplier = $status === Swap::STATUS_ACCEPTED_BY_SUPPLIER;
        $acceptedBySite = $status === Swap::STATUS_ACCEPTED_BY_SITE;
        $rejected = in_array($status, [
            Swap::STATUS_REJECTED_BY_SITE,
            Swap::STATUS_REJECTED_BY_SUPPLIER,
        ], true);

        $txtId = 'SEED-SWAP-' . $index;

        $supplierRequest = null;
        $supplierResponse = null;

        if ($supplierFlow) {
            // Always pair request + response (admin detail shows both columns together)
            $supplierRequest = [
                'from_currency_symbol' => $from->symbol,
                'to_currency_symbol' => $to->symbol,
                'from_currency_amount' => $fromAmount,
                'to_currency_amount' => $toAmount,
                'side' => 'buy',
                'supplier' => Swap::SUPPLER_GATE_IO,
            ];

            if ($acceptedBySupplier) {
                $supplierResponse = [
                    'status' => 1,
                    'id' => $txtId,
                    'buy_amount' => (float) $toFinal,
                    'response' => [
                        'order_id' => $txtId,
                        'filled' => (float) $toFinal,
                        'currency' => $to->symbol,
                    ],
                ];
            } elseif ($status === Swap::STATUS_REJECTED_BY_SUPPLIER) {
                $supplierResponse = [
                    'status' => 0,
                    'id' => $txtId,
                    'description' => 'Rejected by supplier (seeded demo)',
                    'buy_amount' => 0,
                    'response' => [
                        'order_id' => $txtId,
                        'message' => 'Order rejected by supplier',
                        'currency' => $to->symbol,
                    ],
                ];
            } else {
                // waiting_by_supplier
                $supplierResponse = [
                    'status' => 0,
                    'id' => $txtId,
                    'description' => 'Waiting for supplier confirmation',
                    'buy_amount' => 0,
                    'response' => [
                        'order_id' => $txtId,
                        'message' => 'Pending supplier confirmation',
                        'currency' => $to->symbol,
                    ],
                ];
            }
        }

        $swap = Swap::create([
            'user_id' => $user->id,
            'status' => $status,
            'supplier' => $supplierFlow ? Swap::SUPPLER_GATE_IO : Swap::SUPPLIER_MANUAL,
            'from_currency_id' => $from->id,
            'to_currency_id' => $to->id,
            'from_currency_symbol' => $from->symbol,
            'to_currency_symbol' => $to->symbol,
            'from_amount' => $fromAmount,
            'to_amount' => $toAmount,
            'to_final' => $toFinal,
            'from_price' => $fromPrice,
            'to_price' => $toPrice,
            'fee' => $fee,
            'from_total' => $fromAmount * $fromPrice,
            'to_total' => $toFinal * $toPrice,
            'real_from_price' => $fromPrice,
            'real_to_price' => $toPrice,
            'fee_amount' => $fee * ($toPrice > 0 ? $toPrice : 1),
            'description' => $rejected ? 'Rejected (seeded demo)' : 'seeded-demo-trade',
            'txt_id' => $txtId,
            'to_amount_by_supplier' => $acceptedBySupplier ? (float) $toFinal : 0.0,
            'usdt_price' => 98000.0,
            'swap_metod' => $data['swap_metod'] ?? Option::SWAP_METHOD_INTERNATIONAL,
            'usdt_irt_sell_price' => 98500.0,
            'usdt_irt_buy_price' => 97500.0,
            'done_at_by_site' => $acceptedBySite ? now()->subHours(($index % 24) + 1) : null,
            'done_at_by_supplier' => $acceptedBySupplier ? now()->subHours(($index % 24) + 1) : null,
            'supplier_request' => $supplierRequest,
            'supplier_response' => $supplierResponse,
        ]);

        $swap = $swap->fresh();

        // supplier statuses must always have BOTH request and response as arrays
        if (in_array($swap->status, [
            Swap::STATUS_WAITING_BY_SUPPLIER,
            Swap::STATUS_ACCEPTED_BY_SUPPLIER,
            Swap::STATUS_REJECTED_BY_SUPPLIER,
        ], true)) {
            if (!is_array($swap->supplier_request) || $swap->supplier_request === []) {
                throw new RuntimeException("Swap #{$swap->id} ({$swap->status}): supplier_request array is required next to response");
            }
            if (!is_array($swap->supplier_response) || $swap->supplier_response === []) {
                throw new RuntimeException("Swap #{$swap->id} ({$swap->status}): supplier_response array is required next to request");
            }
        }

        return $swap;
    }

    /**
     * Mirror admin list + detail view requirements (production partial uses foreach on arrays).
     */
    private function assertCompatibleWithAdminViews(array $ids): void
    {
        $swaps = Swap::with('user')->whereIn('id', $ids)->get();

        foreach ($swaps as $swap) {
            if (!$swap->user) {
                throw new RuntimeException("Swap #{$swap->id}: user missing (list/detail link breaks)");
            }

            // list + detail numeric fields
            foreach (
                [
                    'from_amount',
                    'to_amount',
                    'fee',
                    'from_price',
                    'to_price',
                    'to_final',
                    'to_amount_by_supplier',
                    'usdt_price',
                    'usdt_irt_sell_price',
                    'usdt_irt_buy_price',
                ] as $field
            ) {
                if ($swap->{$field} === null) {
                    throw new RuntimeException("Swap #{$swap->id}: {$field} is null (toCurrency breaks)");
                }
                toCurrency($swap->{$field});
            }

            $swap->user->getFullInfo();
            Swap::getStatus($swap->status);
            $swap->getCreateAt();

            // supplier request/response must always come as a pair of arrays
            $hasRequest = is_array($swap->supplier_request) && $swap->supplier_request !== [];
            $hasResponse = is_array($swap->supplier_response) && $swap->supplier_response !== [];
            if ($hasRequest !== $hasResponse) {
                throw new RuntimeException(
                    "Swap #{$swap->id}: supplier_request and supplier_response must both be set together"
                );
            }

            foreach (['supplier_request', 'supplier_response'] as $jsonField) {
                $value = $swap->{$jsonField};
                if (!filled($value)) {
                    continue;
                }
                if (!is_array($value)) {
                    throw new RuntimeException(
                        "Swap #{$swap->id}: {$jsonField} must be array for detail view, got " . gettype($value)
                    );
                }
                foreach ($value as $key => $item) {
                    is_array($item);
                }
            }

            if (in_array($swap->status, [
                Swap::STATUS_WAITING_BY_SUPPLIER,
                Swap::STATUS_ACCEPTED_BY_SUPPLIER,
                Swap::STATUS_REJECTED_BY_SUPPLIER,
            ], true) && (!$hasRequest || !$hasResponse)) {
                throw new RuntimeException(
                    "Swap #{$swap->id} ({$swap->status}): both supplier_request and supplier_response are required"
                );
            }

            if (is_array($swap->supplier_response)) {
                $swap->supplier_response['status'] ?? null;
                if (!empty($swap->supplier_response['response']) && !is_array($swap->supplier_response['response'])) {
                    throw new RuntimeException("Swap #{$swap->id}: supplier_response.response must be array");
                }
            }
        }
    }
}
