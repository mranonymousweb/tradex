'use client';

import { ReactNode, useEffect } from 'react';
import { useQuery } from '@tanstack/react-query';
import { useSettings } from '@/providers/settings-provider';
import { metaData } from '@/services/metaData.services';
import {
  getNotificationsPopUp,
  getNotificationsTape,
} from '@/services/notification.services';
import { ErrorBoundary } from '../common/error-boundary';
import { NotifAlert } from '../ui/notif-alert';
import { Header } from './components/header';
import { Sidebar } from './components/sidebar';

export function BaseLayout({ children }: { children: ReactNode }) {
  const { settings, setOption } = useSettings();
  const { data: notifPopup } = useQuery({
    queryKey: ['notifications-pop_up'],
    queryFn: () => getNotificationsPopUp(),
  });
  const { data: notifTape } = useQuery({
    queryKey: ['notifications-tape'],
    queryFn: () => getNotificationsTape(),
  });

  const { data: meta } = useQuery({
    queryKey: ['meta'],
    queryFn: () => metaData(),
  });

  const logo = {
    lightLogo: meta?.meta?.meta_data_logo,
    darkLogo: meta?.meta?.meta_data_dark_logo,
    favicon: meta?.meta.meta_data_favicon,
  };

  useEffect(() => {
    const bodyClass = document.body.classList;

    if (settings.layouts.demo1.sidebarCollapse) {
      bodyClass.add('sidebar-collapse');
    } else {
      bodyClass.remove('sidebar-collapse');
    }
  }, [settings]); // Runs only on settings update

  useEffect(() => {
    // Set current layout
    setOption('layout', 'demo1');
  }, [setOption]);

  return (
    <>
      <Sidebar logo={logo} />

      <div className="wrapper flex grow flex-col">
        <Header logo={logo} />

        <main className="grow pt-2 pb-5" role="content">
          <NotifAlert popup_notif={notifPopup} notifTape={notifTape} />

          <ErrorBoundary>{children}</ErrorBoundary>
        </main>
      </div>
    </>
  );
}
