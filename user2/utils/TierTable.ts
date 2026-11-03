import { toast } from 'sonner';

type Tier = {
  min: number;
  max: number;
  leverage: number;
  maintenanceMarginRate: number;
  maintenanceAmount: number;
};

const tierTable: Tier[] = [
  {
    min: 0,
    max: 50000,
    leverage: 125,
    maintenanceMarginRate: 0.004,
    maintenanceAmount: 0,
  },
  {
    min: 50000,
    max: 250000,
    leverage: 100,
    maintenanceMarginRate: 0.005,
    maintenanceAmount: 50,
  },
  {
    min: 250000,
    max: 3000000,
    leverage: 50,
    maintenanceMarginRate: 0.01,
    maintenanceAmount: 1300,
  },
  {
    min: 3000000,
    max: 15000000,
    leverage: 20,
    maintenanceMarginRate: 0.025,
    maintenanceAmount: 46300,
  },
  {
    min: 15000000,
    max: 30000000,
    leverage: 10,
    maintenanceMarginRate: 0.05,
    maintenanceAmount: 421300,
  },
  {
    min: 30000000,
    max: 80000000,
    leverage: 5,
    maintenanceMarginRate: 0.1,
    maintenanceAmount: 1921300,
  },
  {
    min: 80000000,
    max: 100000000,
    leverage: 4,
    maintenanceMarginRate: 0.125,
    maintenanceAmount: 3921300,
  },
  {
    min: 100000000,
    max: 200000000,
    leverage: 3,
    maintenanceMarginRate: 0.15,
    maintenanceAmount: 6421300,
  },
  {
    min: 200000000,
    max: 300000000,
    leverage: 2,
    maintenanceMarginRate: 0.25,
    maintenanceAmount: 26421300,
  },
  {
    min: 300000000,
    max: 500000000,
    leverage: 1,
    maintenanceMarginRate: 0.5,
    maintenanceAmount: 101421300,
  },
];

export function getTier(positionValue: number, selectedLeverage: number): Tier {
  const tier: any = tierTable.find(
    (t) => positionValue >= t.min && positionValue < t.max,
  );
  if (!tier) throw new Error('Position value exceeds max tier range.');

  if (selectedLeverage >= tier.leverage) {
    toast.error(
      `Selected leverage (${selectedLeverage}x) exceeds the maximum allowed (${tier.leverage}x) for this position size.`,
    );
  }
  return tier;
}

export const maxAmount = (leverage: number) => {
  const tier = tierTable.find((t) => t.leverage <= leverage);
  return tier;
};
