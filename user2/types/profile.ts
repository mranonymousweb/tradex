export type SimpleRowField = 'firstName' | 'lastName' | 'avatar';
export type VerifyRowField = 'email' | 'mobile' | 'password';

export interface PackageType {
  id: number;
  name: string;
  withdrawal_max: number;
  withdrawal_min_fiat: string;
  deposit_max_fiat: string;
  deposit_min_fiat: string;
  fee_fiat: string;
  fiat_maker_fee: string;
  fiat_taker_fee: string;
  internal_transfer_fee: string;
  deposit: boolean;
  withdrawal: boolean;
  currency_maker_fee: number;
  currency_maker_fee_max: number;
  currency_taker_fee: number;
  currency_taker_fee_max: number;
  status: 'active' | 'inactive' | string; // اگر فقط active داریم می‌تونه تک‌ مقدار باشد
  amount_to_grant: number;
  marketing_profit: string;
  currency_min_deposit: string;
  currency_min_withdrawal: string;
  is_user_package: boolean;
}

export interface Device {
  id: number;
  ip: number;
  ip_details: {
    country: string;
    city: string;
  };
  browser: string;
  device: string;
  platform: string;
  last_used_at: string;
}
