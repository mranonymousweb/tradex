export interface TypeBank {
  id: number;
  name: string;
  icon: string;
}
export enum StatusSaturn {
  ACTIVE = 'active',
  INACTIVE = 'inactive',
}

export interface TypeCurrencyBank {
  id: number;
  name: string;
  icon: string;
  symbol: string;
  saturn_verification: StatusSaturn;
}

export interface AddStoreCard {
  bank_id: string;
  card_number: string;
  currency_id: string;
  iban: string;
}

export type Status = 'active' | 'inactive' | 'waiting' | 'rejected';

export enum StatusBank {
  ACTIVE = 'active',
  INACTIVE = 'inactive',
  WAITING = 'waiting',
  REJECTED = 'rejected',
}

export interface bankRow {
  id: number;
  bank: {
    name: string;
  };
  card_number: string;
  iban: string;
  currency: any;
  created_at: string;
  status: Status;
  description: string;
}
