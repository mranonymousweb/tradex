import { T } from '@/types';
import { type MenuConfig } from './types';

export const MENU_MEGA = (t: T) => {
  const itesm: MenuConfig = [
    { title: t('header_title_1'), path: '/' },
    { title: t('header_title_2'), path: '/marketing' },
    { title: t('header_title_3'), path: '/trade/spot?market=BTC_USDT' },
  ];
  return itesm;
};

export const I18N_LANGUAGES = [
  {
    code: 'en',
    name: 'English',
    shortName: 'EN',
    direction: 'ltr',
    flag: '/media/flags/united-states.svg',
  },
  {
    code: 'fa',
    name: 'persian',
    shortName: 'IR',
    direction: 'rtl',
    flag: '/media/flags/iran.svg',
  },
  {
    code: 'ar',
    name: 'Arabic',
    shortName: 'AR',
    direction: 'rtl',
    flag: '/media/flags/saudi-arabia.svg',
  },
  {
    code: 'es',
    name: 'Spanish',
    shortName: 'ES',
    direction: 'ltr',
    flag: '/media/flags/spain.svg',
  },
  {
    code: 'de',
    name: 'German',
    shortName: 'DE',
    direction: 'ltr',
    flag: '/media/flags/germany.svg',
  },
  {
    code: 'ch',
    name: 'Chinese',
    shortName: 'CH',
    direction: 'ltr',
    flag: '/media/flags/china.svg',
  },
  {
    code: 'ru',
    name: 'Russian',
    shortName: 'RU',
    direction: 'ltr',
    flag: '/media/flags/russia.svg',
  },
  {
    code: 'tu',
    name: 'Turkish',
    shortName: 'TU',
    direction: 'ltr',
    flag: '/media/flags/turkey.svg',
  },
];
