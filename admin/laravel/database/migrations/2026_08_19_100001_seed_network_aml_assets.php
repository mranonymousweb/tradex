<?php

use App\Models\Network;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Map exchange network symbols to AMLBot asset codes.
     * Unsupported networks (SUI, SEI, FIL, ...) are intentionally omitted.
     */
    protected array $networkAmlAssets = [
        'BTC' => 'BTC',
        'BITCOIN' => 'BTC',
        'ETH' => 'ETH',
        'ERC20' => 'ETH',
        'ETHEREUM' => 'ETH',
        'TRX' => 'TRX',
        'TRC20' => 'TRX',
        'TRON' => 'TRX',
        'BSC' => 'BSC',
        'BEP20' => 'BSC',
        'BNB' => 'BSC',
        'MATIC' => 'MATIC',
        'POLYGON' => 'MATIC',
        'ARB' => 'ARB',
        'ARBITRUM' => 'ARB',
        'ARBEVM' => 'ARB',
        'OP' => 'OP',
        'OPTIMISM' => 'OP',
        'BASE' => 'BASE',
        'BASEEVM' => 'BASE',
        'AVAX' => 'AVAX',
        'AVALANCHE' => 'AVAX',
        'SOL' => 'SOL',
        'SOLANA' => 'SOL',
        'TON' => 'TON',
        'JETTON' => 'TON',
        'LTC' => 'LTC',
        'BCH' => 'BCH',
        'XRP' => 'XRP',
        'DOGE' => 'DOGE',
        'ADA' => 'ADA',
        'DOT' => 'DOT',
        'NEAR' => 'NEAR',
        'XTZ' => 'XTZ',
        'APT' => 'APT',
        'ALGO' => 'ALGO',
        'CELO' => 'CELO',
        'ZEC' => 'ZEC',
        'BSV' => 'BSV',
        'ETC' => 'ETC',
        'XLM' => 'XLM',
    ];

    public function up(): void
    {
        foreach ($this->networkAmlAssets as $symbol => $amlAsset) {
            DB::table('networks')
                ->whereRaw('UPPER(symbol) = ?', [strtoupper($symbol)])
                ->whereNull('aml_asset')
                ->update(['aml_asset' => $amlAsset]);
        }
    }

    public function down(): void
    {
        $symbols = array_keys($this->networkAmlAssets);

        Network::query()
            ->where(function ($query) use ($symbols) {
                foreach ($symbols as $symbol) {
                    $query->orWhereRaw('UPPER(symbol) = ?', [strtoupper($symbol)]);
                }
            })
            ->update(['aml_asset' => null]);
    }
};
