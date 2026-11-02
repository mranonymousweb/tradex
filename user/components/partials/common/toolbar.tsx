'use client';

import { ReactNode } from 'react';
import { usePathname } from 'next/navigation';
import { useQuery } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { MENU_SIDEBAR } from '@/config/general.config';
import { useMenu } from '@/hooks/use-menu';
import { metaData } from '@/services/metaData.services';

const Toolbar = ({ children }: { children: ReactNode }) => {
  return (
    <div className="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
      {children}
    </div>
  );
};

const ToolbarActions = ({ children }: { children: ReactNode }) => {
  return <div className="flex items-center gap-2.5">{children}</div>;
};

const ToolbarPageTitle = ({ text }: { text?: string }) => {
  const t = useTranslations('sidebar');
  const pathname = usePathname();
  const { getCurrentItem } = useMenu(pathname);
  const { data: meta } = useQuery({
    queryKey: ['meta_data'],
    queryFn: () => metaData(),
  });
  const item = getCurrentItem(MENU_SIDEBAR(meta, t));

  return (
    <h1 className="text-xl font-medium leading-none text-mono">
      {text ?? item?.title}
    </h1>
  );
};

const ToolbarDescription = ({ children }: { children: ReactNode }) => {
  return (
    <div className="flex items-center gap-2 text-sm font-normal text-secondary-foreground mt-1">
      {children}
    </div>
  );
};

const ToolbarHeading = ({ children }: { children: ReactNode }) => {
  return <div className="flex flex-col justify-center gap-2">{children}</div>;
};

export {
  Toolbar,
  ToolbarActions,
  ToolbarPageTitle,
  ToolbarHeading,
  ToolbarDescription,
};
