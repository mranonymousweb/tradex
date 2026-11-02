export enum MarketOpeningHourDay {
  SATURDAY = 'saturday',
  SUNDAY = 'sunday',
  MONDAY = 'monday',
  TUESDAY = 'tuesday',
  WEDNESDAY = 'wednesday',
  THURSDAY = 'thursday',
  FRIDAY = 'friday',
}

export const MARKET_OPENING_HOUR_DAYS = [
  MarketOpeningHourDay.SATURDAY,
  MarketOpeningHourDay.SUNDAY,
  MarketOpeningHourDay.MONDAY,
  MarketOpeningHourDay.TUESDAY,
  MarketOpeningHourDay.WEDNESDAY,
  MarketOpeningHourDay.THURSDAY,
  MarketOpeningHourDay.FRIDAY,
] as const;
