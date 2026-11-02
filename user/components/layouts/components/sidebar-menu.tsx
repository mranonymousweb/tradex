'use client';

import { JSX, useCallback } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { useQuery } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { MENU_SIDEBAR } from '@/config/general.config';
import { MenuConfig, MenuItem } from '@/config/types';
import { cn } from '@/lib/utils';
import { metaData } from '@/services/metaData.services';
import {
    AccordionMenu,
    AccordionMenuClassNames,
    AccordionMenuGroup,
    AccordionMenuItem,
    AccordionMenuSub,
    AccordionMenuSubContent,
    AccordionMenuSubTrigger,
} from '@/components/ui/accordion-menu';
import LogoutSidebar from './logout-sidebar';

export function SidebarMenu() {
    const t = useTranslations('sidebar');
    const pathname = usePathname();
    const { data: meta } = useQuery({
        queryKey: ['meta_data'],
        queryFn: () => metaData(),
    });
    const menuSidebar = MENU_SIDEBAR(meta, t);
    // Memoize matchPath to prevent unnecessary re-renders
    const matchPath = useCallback(
        (path: string): boolean =>
            path === pathname || (path.length > 1 && pathname.startsWith(path)),
        [pathname],
    );

    // Global classNames for consistent styling
    const classNames: AccordionMenuClassNames = {
        root: 'lg:ps-1 space-y-3 pb-0',
        group: 'gap-px',
        label:
            'uppercase text-xs font-medium text-muted-foreground/70 pt-2.25 pb-px',
        separator: '',
        item: 'h-8 hover:bg-secondary/50 text-secondary-foreground hover:text-primary data-[selected=true]:text-primary data-[selected=true]:bg-secondary/50 data-[selected=true]:font-medium',
        sub: '',
        subTrigger:
            'h-8 hover:bg-secondary/50 text-secondary-foreground hover:text-primary data-[selected=true]:text-primary data-[selected=true]:bg-secondary/50 data-[selected=true]:font-medium',
        subContent: 'py-0',
        indicator: '',
    };

    const buildMenu = (items: MenuConfig): JSX.Element[] => {
        return items.map((item: MenuItem, index: number) => {
            return buildMenuItemRoot(item, index);
        });
    };

    const buildMenuItemRoot = (item: MenuItem, index: number): JSX.Element => {
        if (item.children) {
            return (
                <AccordionMenuSub key={index} value={item.path || `root-${index}`}>
                    <AccordionMenuSubTrigger className="text-sm font-medium">
                        {item.icon && <i className={item.icon}></i>}
                        <span data-slot="accordion-menu-title">{item.title}</span>
                    </AccordionMenuSubTrigger>
                    <AccordionMenuSubContent
                        type="single"
                        collapsible
                        parentValue={item.path || `root-${index}`}
                        className="!ps-0 menu-accordion gap-0.5 relative before:absolute before:start-[17px] before:top-0 before:bottom-0 before:border-s before:border-gray-200"
                    >
                        <AccordionMenuGroup>
                            {buildMenuItemChildren(item.children, 1)}
                        </AccordionMenuGroup>
                    </AccordionMenuSubContent>
                </AccordionMenuSub>
            );
        } else {
            return (
                <AccordionMenuItem
                    key={index}
                    value={item.path || ''}
                    mode={item.mode || ''}
                    data-slot="accordion-menu"
                    className="text-sm font-medium p-0"
                >
                    <Link href={item.path || '#'} className="w-full h-full px-2 block">
                        {item.icon && <i className={item.icon}></i>}
                        <span data-slot="accordion-menu-title">{item.title}</span>
                        <span className="text-muted-foreground font-medium">
                            {item.heading}
                        </span>
                    </Link>
                </AccordionMenuItem>
            );
        }
    };

    const buildMenuItemChildren = (
        items: MenuConfig,
        level: number = 0,
    ): JSX.Element[] => {
        return items.map((item: MenuItem, index: number) => {
            return buildMenuItemChild(item, index, level);
        });
    };

    const buildMenuItemChild = (
        item: MenuItem,
        index: number,
        level: number = 0,
    ): JSX.Element => {
        if (item.children) {
            return (
                <AccordionMenuSub
                    key={index}
                    value={item.path || `child-${level}-${index}`}
                >
                    <AccordionMenuSubTrigger className="text-[13px]">
                        {item.collapse ? (
                            <span className="text-muted-foreground">
                                <span className="hidden [[data-state=open]>span>&]:inline">
                                    {item.collapseTitle}
                                </span>
                                <span className="inline [[data-state=open]>span>&]:hidden">
                                    {item.expandTitle}
                                </span>
                            </span>
                        ) : (
                            item.title
                        )}
                    </AccordionMenuSubTrigger>
                    <AccordionMenuSubContent
                        type="single"
                        collapsible
                        parentValue={item.path || `child-${level}-${index}`}
                        className={cn(
                            'ps-4',
                            !item.collapse && 'relative',
                            !item.collapse && (level > 0 ? '' : ''),
                        )}
                    >
                        <AccordionMenuGroup>
                            {buildMenuItemChildren(
                                item.children,
                                item.collapse ? level : level + 1,
                            )}
                        </AccordionMenuGroup>
                    </AccordionMenuSubContent>
                </AccordionMenuSub>
            );
        } else {
            return (
                <AccordionMenuItem
                    key={index}
                    value={item.path || ''}
                    className="text-[13px] "
                >
                    <Link
                        href={item.path || '#'}
                        className={`accordion-menu--item hover:bg-secondary/50 flex items-center py-2.5 rounded-sm px-2.5`}
                    >
                        <span className="w-[6px] opacity-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full before:translate-x-1/2 before:-translate-y-1/2 before:bg-primary hover:before:bg-primary"></span>
                        <span className="mr-2 text-2sm font-medium text-secondary-foreground  menu-item-active:text-primary menu-item-active:font-semibold menu-link-hover:!text-primary">
                            {item.title}
                        </span>
                    </Link>
                </AccordionMenuItem>
            );
        }
    };

    return (
        <div className="relative flex h-full flex-col">
            <div className="kt-scrollable-y-hover min-h-0 flex-1 py-5 px-3">
                <AccordionMenu
                    selectedValue={pathname}
                    matchPath={matchPath}
                    type="single"
                    collapsible
                    classNames={classNames}
                >
                    {buildMenu(menuSidebar)}
                </AccordionMenu>
            </div>
            <div className="shrink-0">
                <LogoutSidebar />
                <div className="flex items-center justify-center pb-2 pt-1">
                    <span className="text-xs text-muted-foreground">V 1.0.1</span>
                </div>
            </div>
        </div>
    );
}
