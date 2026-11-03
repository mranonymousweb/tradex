'use client';

import { useTranslations } from 'next-intl';
import {
  ScrollspyMenu,
  ScrollspyMenuItems,
} from '@/components/partials/navbar/scrollspy-menu';

export function AccountSettingsSidebar() {
  const t = useTranslations('profile');
  const items: ScrollspyMenuItems = [
    {
      title: t('info_account'),
      target: 'basic_settings',
      active: true,
    },
    {
      title: t('security'),
      children: [
        {
          title: t('password'),
          target: 'auth_password',
        },
        {
          title: t('email'),
          target: 'auth_email',
        },
        {
          title: t('google_auth'),
          target: 'auth_two_factor',
        },
        {
          title: t('session'),
          target: 'device',
        },
      ],
    },
    {
      title: t('foreign_service'),
      children: [
        {
          title: t('api_manager'),
          target: 'external_services_manage_api',
        },
      ],
    },
  ];

  return <ScrollspyMenu items={items} />;
}
