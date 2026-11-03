'use client';

import { Fragment } from 'react';
import { usePathname } from 'next/navigation';
import { useQuery } from '@tanstack/react-query';
import { ChevronRight } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { MENU_SIDEBAR } from '@/config/general.config';
import { MenuItem } from '@/config/types';
import { cn } from '@/lib/utils';
import { useMenu } from '@/hooks/use-menu';
import { metaData } from '@/services/metaData.services';

export function Breadcrumb() {
  const t = useTranslations('sidebar');
  const pathname = usePathname();
  const { getBreadcrumb, isActive } = useMenu(pathname);
  const { data: meta } = useQuery({
    queryKey: ['meta_data'],
    queryFn: () => metaData(),
  });
  const items: MenuItem[] = getBreadcrumb(MENU_SIDEBAR(meta, t));

  if (items.length === 0) {
    return null;
  }

  return (
    <div className="flex items-center gap-1.25 text-xs lg:text-sm font-medium mb-2.5 lg:mb-0">
      {items.map((item, index) => {
        const last = index === items.length - 1;
        const active = item.path ? isActive(item.path) : false;

        return (
          <Fragment key={`root-${index}`}>
            <span
              className={cn(active ? 'text-mono' : 'text-secondary-foreground')}
              key={`item-${index}`}
            >
              {item.title}
            </span>
            {!last && (
              <ChevronRight
                className="size-3.5 text-muted-foreground"
                key={`separator-${index}`}
              />
            )}
          </Fragment>
        );
      })}
    </div>
  );
}
