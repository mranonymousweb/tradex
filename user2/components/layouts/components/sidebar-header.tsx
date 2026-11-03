'use client';

import Link from 'next/link';
import { Logo } from '@/types';
import { ChevronFirst } from 'lucide-react';
import { cn } from '@/lib/utils';
import { useSettings } from '@/providers/settings-provider';
import { Button } from '@/components/ui/button';

export function SidebarHeader({ logo }: { logo: Logo }) {
  const { settings, storeOption } = useSettings();

  const handleToggleClick = () => {
    storeOption(
      'layouts.demo1.sidebarCollapse',
      !settings.layouts.demo1.sidebarCollapse,
    );
  };

  return (
    <div className="sidebar-header h-20 hidden lg:flex items-center relative justify-between p-3 lg:px-6 shrink-0 border-b">
      <Link href="/">
        <div className="dark:hidden">
          <img
            src={logo?.lightLogo}
            className="default-logo w-fit h-[25px] object-contain"
            alt=""
          />
          <img
            src={logo?.favicon}
            className="small-logo w-fit h-[12px] object-contain"
            alt="Mini Logo"
          />
        </div>
        <div className="hidden dark:block">
          <img
            src={logo?.darkLogo}
            className="default-logo w-fit h-[25px] object-contain"
            alt="Default Dark Logo"
          />
          <img
            src={logo?.favicon}
            className="small-logo w-fit h-[12px] object-contain"
            alt="Mini Logo"
          />
        </div>
      </Link>
      <Button
        onClick={handleToggleClick}
        size="sm"
        mode="icon"
        variant="outline"
        className={cn(
          'size-7 absolute start-full top-2/4 rtl:translate-x-2/4 -translate-x-2/4 -translate-y-2/4',
          settings.layouts.demo1.sidebarCollapse
            ? 'ltr:rotate-180'
            : 'rtl:rotate-180',
        )}
      >
        <ChevronFirst className="size-4!" />
      </Button>
    </div>
  );
}
