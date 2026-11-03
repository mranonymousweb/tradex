export type Locale = (typeof locales)[number];

function isLocale(value: any): value is Locale {
  return locales.includes(value);
}
export const locales = [
  'fa',
  'en',
  'ar',
  'es',
  'de',
  'ch',
  'ru',
  'tu',
] as const;
export const defaultLocale: Locale = isLocale(
  process.env.NEXT_PUBLIC_DEFAULT_LANGUAGE,
)
  ? process.env.NEXT_PUBLIC_DEFAULT_LANGUAGE
  : 'fa';
