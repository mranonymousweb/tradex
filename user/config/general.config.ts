import { Bell } from 'lucide-react';
import { MenuConfig } from './types';

export type T = (key: string) => string;

export const MENU_SIDEBAR = (meta: any, t: T): MenuConfig => {
  const isFuturesEnabled = process.env.NEXT_PUBLIC_ENABLE_FUTURES === 'true';
  const isSpotEnabled = process.env.NEXT_PUBLIC_ENABLE_SPOT === 'true';

  const tradeLinks = [
    ...(isSpotEnabled
      ? [
        {
          title: t('trade_spot'),
          path: '/trade/spot?market=BTC_USDT',
        },
      ]
      : []),

    ...(isFuturesEnabled
      ? [
        {
          title: t('trade_futuers'),
          path: '/trade/futures',
        },
      ]
      : []),
  ];

  const walletLinks = [
    {
      title: t('wallet_deposit'),
      path: '/wallet/deposit',
    },
    {
      title: t('wallet_withdrawal'),
      path: '/wallet/withdrawal',
    },

    ...(isFuturesEnabled
      ? [
        {
          title: t('wallet_futures'),
          path: '/wallet/futures',
        },
      ]
      : []),
    {
      title: t('reports_transactions'),
      path: '/wallet/reports',
    },
  ];

  const items = [
    {
      title: t('dashboard'),
      icon: 'ki-outline ki-home text-lg',
      path: '/',
    },
    ...(isFuturesEnabled || isSpotEnabled
      ? [
        {
          title: t('trade_market'),
          icon: 'ki-outline ki-chart-simple text-lg',
          children: tradeLinks,
        },
      ]
      : []),
    {
      title: t('wallet'),
      icon: 'ki-outline ki-wallet text-lg',
      children: walletLinks,
      path: '/wallet',
    },

    {
      title: t('assets'),
      icon: 'ki-outline ki-dollar text-lg',
      path: '/assets',
    },
    {
      title: t('swap'),
      icon: 'ki-outline ki-arrows-circle text-lg',
      path: '/swap',
    },
    {
      title: t('bank_card'),
      icon: 'ki-outline ki-two-credit-cart text-lg',
      path: '/user-bank',
    },
    {
      title: t('markets'),
      icon: 'ki-outline ki-chart-line-up-2 text-lg',
      path: '/markets',
    },
    {
      title: t('balance_transform'),
      icon: 'ki-outline ki-arrow-right-left text-lg',
      path: '/conversion',
    },
    {
      title: t('stock'),
      icon: 'ki-outline ki-tag text-lg',
      path: '/stock',
    },
    {
      title: t('marketing'),
      icon: 'ki-outline ki-users text-lg',
      path: '/marketing',
    },
    {
      title: t('notification'),
      icon: 'ki-outline ki-notification text-lg',
      path: '/notifications',
    },
    {
      title: t('ticket'),
      icon: 'ki-outline ki-message-text-2 text-lg',
      children: [
        { title: t('ticket_add'), path: '/ticket/add' },
        { title: t('ticket_show'), path: '/ticket/list' },
      ],
    },
    { heading: t('other') },
    {
      title: t('fee'),
      icon: Bell,
      mode: 'link',
      path: meta?.url?.user_fee ?? '/#',
    },
    {
      title: t('guide'),
      icon: Bell,
      mode: 'link',
      path: meta?.url?.user_guide ?? '/#',
    },
    {
      title: t('rule'),
      icon: Bell,
      mode: 'link',
      path: meta?.url?.user_rule ?? '/#',
    },
    {
      title: t('about'),
      icon: Bell,
      mode: 'link',
      path: meta?.url?.meta_data_about_us_link ?? '/#',
    },
  ];
  return items;
};

//Header Menu Desktop
export const MENU_HEADER = (meta: any, t: T): MenuConfig => {
  const isFuturesEnabled = process.env.NEXT_PUBLIC_ENABLE_FUTURES === 'true';
  const isSpotEnabled = process.env.NEXT_PUBLIC_ENABLE_SPOT === 'true';

  const tradeLinks = [
    ...(isSpotEnabled
      ? [
        {
          title: t('trade_spot'),
          path: '/trade/spot?market=BTC_USDT',
        },
      ]
      : []),

    ...(isFuturesEnabled
      ? [
        {
          title: t('trade_futuers'),
          path: '/trade/futures',
        },
      ]
      : []),
  ];

  const walletLinks = [
    {
      title: t('wallet_deposit'),
      path: '/wallet/deposit',
    },
    {
      title: t('wallet_withdrawal'),
      path: '/wallet/withdrawal',
    },
    {
      title: t('assets'),
      path: '/assets',
    },
    {
      title: t('balance_transform'),
      path: '/conversion',
    },

    ...(isFuturesEnabled
      ? [
        {
          title: t('wallet_futures'),
          path: '/wallet/futures',
        },
      ]
      : []),
    {
      title: t('reports_transactions'),
      path: '/wallet/reports',
    },
  ];

  const items = [
    {
      title: t('dashboard'),
      icon: 'ki-outline ki-home text-lg',
      path: '/',
    },
    ...(isFuturesEnabled || isSpotEnabled
      ? [
        {
          title: t('trade_market'),
          icon: 'ki-outline ki-chart-simple text-lg',
          children: tradeLinks,
        },
      ]
      : []),
    {
      title: t('wallet'),
      icon: 'ki-outline ki-wallet text-lg',
      children: walletLinks,
      path: '/wallet',
    },

    {
      title: t('swap'),
      icon: 'ki-outline ki-arrows-circle text-lg',
      path: '/swap',
    },
    {
      title: t('markets'),
      icon: 'ki-outline ki-chart-line-up-2 text-lg',
      path: '/markets',
    },

    {
      title: t('stock'),
      icon: 'ki-outline ki-tag text-lg',
      path: '/stock',
    },
    {
      title: t('marketing'),
      icon: 'ki-outline ki-users text-lg',
      path: '/marketing',
    },
    {
      title: t('notification'),
      icon: 'ki-outline ki-notification text-lg',
      path: '/notifications',
    },
    {
      title: t('ticket'),
      icon: 'ki-outline ki-message-text-2 text-lg',
      children: [
        { title: t('ticket_add'), path: '/ticket/add' },
        { title: t('ticket_show'), path: '/ticket/list' },
      ],
    },

    {
      title: t('other'),
      icon: 'ki-outline ki-message-text-2 text-lg',
      children: [
        { title: t('fee'), path: meta?.url?.user_fee ?? '/#' },
        { title: t('guide'), path: meta?.url?.user_guide ?? '/#' },
        { title: t('rule'), path: meta?.url?.user_rule ?? '/#' },
        { title: t('about'), path: meta?.url?.user_rule ?? '/#' },
      ],
    },
  ];
  return items;
};
