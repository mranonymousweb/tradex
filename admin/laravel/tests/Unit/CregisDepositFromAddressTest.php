<?php

namespace Tests\Unit;

use App\Services\SupplierExchange\Suppliers\Cregis;
use PHPUnit\Framework\TestCase;

class CregisDepositFromAddressTest extends TestCase
{
    public function test_picks_the_main_amount_sender_not_the_dust_return(): void
    {
        $rows = [
            $this->row([
                'amount' => '0.000002',
                'from_address' => 'TUWoXxgFdXTqnUQveUWrkX7EjNBYB9TL2k',
            ]),
            $this->row([
                'amount' => '14.198004',
                'from_address' => 'TRealSender11111111111111111111111',
            ]),
        ];

        $this->assertSame(
            'TRealSender11111111111111111111111',
            Cregis::pickDepositFromAddress(
                $rows,
                'TEr5Dnkzh2cHQ9TTCJGzTMTEkDsb2RZeL8',
                '14.198004000000000000000000000000'
            )
        );
    }

    public function test_amount_match_keeps_the_five_usdt_sender_on_a_shared_txid(): void
    {
        $rows = [
            $this->row([
                'amount' => '0.000002',
                'from_address' => 'TDustOrEnergyPayer000000000000000',
                'currency' => 'TRX',
            ]),
            $this->row([
                'amount' => '5',
                'from_address' => 'TUserWhoSentFiveUsdt0000000000000',
                'currency' => 'USDT-TRC20',
            ]),
        ];

        $this->assertSame(
            'TUserWhoSentFiveUsdt0000000000000',
            Cregis::pickDepositFromAddress($rows, 'TEr5Dnkzh2cHQ9TTCJGzTMTEkDsb2RZeL8', '5.000000')
        );
    }

    public function test_ignores_cregis_collect_fee_rows(): void
    {
        $rows = [
            $this->row([
                'amount' => '0.000002',
                'from_address' => 'TCollectFee0000000000000000000000',
                'business_type' => 4,
            ]),
            $this->row([
                'amount' => '3.2',
                'from_address' => 'TTonSender00000000000000000000000',
                'business_type' => 3,
            ]),
        ];

        $this->assertSame(
            'TTonSender00000000000000000000000',
            Cregis::pickDepositFromAddress($rows, 'UQCXcMaCwBOfNJR5ocobaAQQCkXFY5BKGpE_q-vWLKz_v5RW', '3.2')
        );
    }

    public function test_drops_our_deposit_address_from_a_comma_separated_from_field(): void
    {
        $deposit = 'rGr8Z79RmwdAZNjQAirbzr1s5oNDdVPLoq';

        $rows = [
            $this->row([
                'amount' => '2.8',
                'from_address' => $deposit.',rXrpRealSender111111111111111111',
                'to_address' => $deposit,
            ]),
        ];

        $this->assertSame(
            'rXrpRealSender111111111111111111',
            Cregis::pickDepositFromAddress($rows, $deposit, '2.8')
        );
    }

    public function test_falls_back_to_largest_incoming_when_amount_is_missing(): void
    {
        $rows = [
            $this->row([
                'amount' => '0.00001',
                'from_address' => 'rTinyReturn000000000000000000000',
            ]),
            $this->row([
                'amount' => '2.8',
                'from_address' => 'rMainXrpSender000000000000000000',
            ]),
        ];

        $this->assertSame(
            'rMainXrpSender000000000000000000',
            Cregis::pickDepositFromAddress($rows, 'rGr8Z79RmwdAZNjQAirbzr1s5oNDdVPLoq')
        );
    }

    public function test_returns_null_when_only_collect_dust_exists(): void
    {
        $rows = [
            $this->row([
                'amount' => '0.000002',
                'from_address' => 'TCollectFee0000000000000000000000',
                'business_type' => 4,
            ]),
        ];

        $this->assertNull(
            Cregis::pickDepositFromAddress($rows, 'TEr5Dnkzh2cHQ9TTCJGzTMTEkDsb2RZeL8', '14.198004')
        );
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function row(array $overrides): array
    {
        return array_merge([
            'status' => 1,
            'business_type' => 3,
            'to_address' => 'TEr5Dnkzh2cHQ9TTCJGzTMTEkDsb2RZeL8',
            'txid' => '20ca3da671681fbba5d2a20ac0b24ba9a941875e72dbfc8692c1ce2ccb06abbc',
        ], $overrides);
    }
}
