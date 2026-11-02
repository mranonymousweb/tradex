/* eslint-disable @typescript-eslint/no-explicit-any */
interface User {
  id: number;
  name: string;
  family: string;
  email: string;
  mobile: string;
  status: number;
  avatar: string;
  address: string | null;
  iranian: boolean;
  marketing_code: string | null;
}

interface Currency {
  id: number;
  name: string;
  symbol: string;
  icon: string;
}

export interface SubsetData {
  user_id: number;
  parent_id: number;
  section: string;
  currency_id: number;
  symbol: string;
  total_amount: number;
  user_amount: string;
  parent_amount: string;
  user: User;
  parent: User;
  currency: Currency;
}

export interface SubsetRow {
  id: number;
  name: string;
  family: string;
  created_at: string;
  total_parent_amount: number;
  code: string;
}

export interface LinkSubsetRow {
  id: number;
  name: string;
  code: string;
  total_amount: string;
  user_count: string;
  section: string;
  is_active: boolean;
  total_parent_amount: number;
  user_percentage: string;
}

export interface LogsSubsetRow {
  user_id: number;
  symbol: string;
  created_at: string;
  total_amount: string;
  parent_amount: string;
  section: string;
}
