import { getUserLocale } from '@/lib/locale';

// Default currency, but you can make this dynamic as well.
const DEFAULT_CURRENCY = 'USD';

/**
 * Get the current locale from i18n.
 * @returns Current locale string (e.g., 'en-US').
 */
const getCurrentLocale = async () => {
  const language = await getUserLocale();
  const formatter = language === 'fa' ? 'fa-IR-u-ca-persia' : 'en-US';
  return formatter || 'fa-IR-u-ca-persia'; // Fallback to 'en-US' if locale is not set
};

/**
 * Format a date to "Dec 7, 2024" format.
 */
export const formatDate = async (date: Date | string) => {
  const locale = await getCurrentLocale();
  const parsedDate = typeof date === 'string' ? new Date(date) : date;
  return new Intl.DateTimeFormat(locale, {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
  }).format(parsedDate);
};

/**
 * Format a date and time to "Dec 7, 2024, 11:41 PM" format.
 */
export const formatDateTime = async (date: Date | string) => {
  const locale = await getCurrentLocale();
  const parsedDate = typeof date === 'string' ? new Date(date) : date;
  return new Intl.DateTimeFormat(locale, {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: 'numeric',
    minute: 'numeric',
    hour12: true,
  }).format(parsedDate);
};

/**
 * Format time to "11:41 PM" format.
 */
export const formatTime = async (date: Date | string) => {
  const locale = await getCurrentLocale();
  const parsedDate = typeof date === 'string' ? new Date(date) : date;
  return new Intl.DateTimeFormat(locale, {
    hour: 'numeric',
    minute: 'numeric',
    hour12: true,
  }).format(parsedDate);
};

/**
 * Format money to a localized currency string.
 */
export const formatMoney = async (
  amount: number,
  currency: string = DEFAULT_CURRENCY,
) => {
  const locale = await getCurrentLocale();
  return new Intl.NumberFormat(locale, {
    style: 'currency',
    currency,
  }).format(amount);
};
