'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { useTranslations } from 'next-intl';
import { MENU_MEGA } from '@/config/menu.config';
import { cn } from '@/lib/utils';
import { useMenu } from '@/hooks/use-menu';
import {
  NavigationMenu,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
} from '@/components/ui/navigation-menu';

export function MegaMenu() {
  const t = useTranslations('header');
  const pathname = usePathname();
  const { isActive } = useMenu(pathname);
  const megaMenu = MENU_MEGA(t);
  const homeItem = megaMenu[0];
  const refralItem = megaMenu[1];
  const futuersItem = megaMenu[2];

  const linkClass = `
        text-sm text-secondary-foreground font-medium 
        hover:text-primary hover:bg-transparent 
        focus:text-primary focus:bg-transparent 
        data-[active=true]:text-primary data-[active=true]:bg-transparent 
        data-[state=open]:text-primary data-[state=open]:bg-transparent
    `;

  return (
    <NavigationMenu>
      <NavigationMenuList className="gap-0">
        {/* Home Item */}
        <NavigationMenuItem>
          <NavigationMenuLink asChild>
            <Link
              href={homeItem.path || '/'}
              className={cn(linkClass)}
              data-active={isActive(homeItem.path) || undefined}
            >
              {homeItem.title}
            </Link>
          </NavigationMenuLink>
        </NavigationMenuItem>

        {/* Refal Item */}
        <NavigationMenuItem>
          <NavigationMenuLink asChild>
            <Link
              href={refralItem.path || '/'}
              className={cn(linkClass)}
              data-active={isActive(refralItem.path) || undefined}
            >
              {refralItem.title}
            </Link>
          </NavigationMenuLink>
        </NavigationMenuItem>

        {/* Futuers Item */}
        <NavigationMenuItem>
          <NavigationMenuLink asChild>
            <Link
              href={futuersItem.path || '/'}
              className={cn(linkClass)}
              data-active={isActive(futuersItem.path) || undefined}
            >
              {futuersItem.title}
            </Link>
          </NavigationMenuLink>
        </NavigationMenuItem>
      </NavigationMenuList>
    </NavigationMenu>
  );
}
