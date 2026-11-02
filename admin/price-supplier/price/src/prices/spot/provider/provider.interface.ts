export const SPOT_PROVIDER_NAME = 'gate_io' as const;

export type SpotProviderName = typeof SPOT_PROVIDER_NAME;

export interface PriceExchange {
  readonly name: SpotProviderName;
  connect(): Promise<void>;
  disconnect(): Promise<void>;
}
