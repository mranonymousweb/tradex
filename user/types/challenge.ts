export interface GatewayType {
  id: number;
  key?: string;
  value: string;
  symbol: string;
}

export interface RadioItem {
  logo: string;
  title: string;
  size: string;
  checked: boolean;
  value: string;
}

export interface Currency {
  id: number;
  name: string;
  symbol: string;
  icon: string;
}

export interface Asset {
  id: number;
  currency_id: number;
  symbol: string;
  balance: string; // چون API رشته برمی‌گردونه
  feature_balance: string; // همینطور
  currency: Currency;
}

export type AssetsResponse = Asset[];

export type challenegStatus =
  | 'new'
  | 'pending'
  | 'answered'
  | 'user_answered'
  | 'closed';

export interface ChallengeRow {
  id: number;
  challenge_id: string;
  challenge_title: string;
  price: string;
  status: number;
  created_at: string;
  initial_balance: number;
}

export interface Challenge {
  id: number;
  slug: string | null;
  step_number: number;
  price: string;
  discount: string;
  type: number;
  marketer_percentage: number;
  status: number;
  title: string;
  description: string;
  irt_price: string | null;
}

export interface ChallengeProperty {
  id: number;
  challenge_id: number;
  step: number;
  status: number;
  initial_balance: number;
  challenge_duration: number;
  min_day: number;
  profit_target: number;
  daily_drawdown: number;
  max_drawdown: number;
  max_position_size: number;
  max_open_trades: number;
  leverage: number | null;
  leverage_from: number | null;
  leverage_to: number | null;
  risk_reset_time: string;
  currencies: string | null;
  hold_overnight_positions: boolean;
  weekend_trading_allowed: boolean;
  force_sl_tp_usage: boolean;
  auto_disable_on_rule_breach: boolean;
  allow_copy_trading: boolean;
  allow_bots_ea: boolean;
  news_time_trading_block: number;
  created_at: string;
  updated_at: string;
}

export interface UserChallenge {
  id: number;
  user_id: number;
  challenge_id: number;
  challenge_title: string;
  status: number;
  price: string;
  type: string;
  created_at: string;
  initial_balance: number;
  activate_step_one_date: string;
  activate_step_two_date: string | null;
  activate_step_three_date: string | null;
  current_step: number;
}

export interface StepInfo {
  step: number;
  daily_reward: Reward;
  weekly_reward: Reward;
  monthly_reward: Reward;
  all_summary: Reward;
  last_balance_change: number;
  active_step: boolean;
  activate_date: string;
  finish_date: string;
  last_balance: string;
}

export interface Steps {
  [key: string]: StepInfo;
}

export interface Transaction {
  id: number;
  amount: string;
  section: number;
  createdAt: string;
  symbol: string;
}

export interface ChallengeResponse {
  challenge: Challenge;
  current_property: ChallengeProperty;
  properties: ChallengeProperty[];
  userChallenge: UserChallenge;
  transaction: Transaction;
  steps: Steps;
}

// Challenge main info
export interface Challenge {
  id: number;
  slug: string | null;
  step_number: number;
  price: string;
  discount: string;
  type: number;
  marketer_percentage: number;
  status: number;
  title: string;
  description: string;
  irt_price: string | null;
}

// Challenge property (rules per step)
export interface ChallengeProperty {
  id: number;
  challenge_id: number;
  step: number;
  status: number;
  initial_balance: number;
  challenge_duration: number;
  min_day: number;
  profit_target: number;
  daily_drawdown: number;
  max_drawdown: number;
  max_position_size: number;
  max_open_trades: number;
  leverage: number | null;
  leverage_from: number | null;
  leverage_to: number | null;
  risk_reset_time: string;
  hold_overnight_positions: boolean;
  weekend_trading_allowed: boolean;
  force_sl_tp_usage: boolean;
  auto_disable_on_rule_breach: boolean;
  allow_copy_trading: boolean;
  allow_bots_ea: boolean;
  news_time_trading_block: number;
  created_at: string;
  updated_at: string;
}

// User’s challenge progress
export interface UserChallenge {
  id: number;
  user_id: number;
  challenge_id: number;
  challenge_title: string;
  status: number;
  price: string;
  type: string;
  created_at: string;
  initial_balance: number;
  activate_step_one_date: string;
  activate_step_two_date: string | null;
  activate_step_three_date: string | null;
  current_step: number;
}

// Transaction log
export interface Transaction {
  id: number;
  amount: string;
  type: number;
  section: number;
  createdAt: string;
}

// Reward details
export interface Reward {
  id?: number;
  user_id: number;
  challenge_id: number;
  drawdown: number;
  drawdown_amount: number;
  trade_count: number;
  current_step: number;
  description?: string | null;
  started_balance: string;
  finished_balance: string;
  total_change_balance_percentage: string | number;
  total_change_balance_amount: string | number;
  created_at?: string;
  updated_at?: string;
}

// Step info
export interface Step {
  step: number;
  daily_reward: Reward;
  weekly_reward: Reward;
  monthly_reward: Reward;
  all_summary: Reward;
  active_step: boolean;
  activate_date: string;
  finish_date: string;
  last_balance: string | number;
  last_balance_change: number;
}

// Main data structure
export interface ChallengeData {
  challenge: Challenge;
  current_property: ChallengeProperty;
  properties: ChallengeProperty[];
  userChallenge: UserChallenge;
  transaction: Transaction;
  steps: {
    [key: number]: Step;
  };
}
