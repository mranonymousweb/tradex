'use client';

import { usePathname } from 'next/navigation';
import { Logo } from '@/types';
import { cn } from '@/lib/utils';
import { useSettings } from '@/providers/settings-provider';
import { SidebarHeader } from './sidebar-header';
import { SidebarMenu } from './sidebar-menu';

export function Sidebar({ logo }: { logo: Logo }) {
  const { settings } = useSettings();
  const pathname = usePathname();
  return (
    <div
      className={cn(
        'sidebar bg-card/70 backdrop-blur-xl lg:border-e lg:border-border relative lg:fixed lg:top-0 lg:bottom-0 lg:z-20 lg:flex flex-col items-stretch shrink-0',
        (settings.layouts.demo1.sidebarTheme === 'dark' ||
          pathname.includes('dark-sidebar')) &&
          'dark',
      )}
    >
      <SidebarHeader logo={logo} />
      <div className="overflow-hidden flex-1">
        <div className="w-(--sidebar-default-width) h-full">
          <SidebarMenu />
        </div>
      </div>
    </div>
  );
}
