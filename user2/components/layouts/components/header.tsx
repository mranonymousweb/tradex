'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { Logo } from '@/types';
import { useQuery } from '@tanstack/react-query';
import { Menu } from 'lucide-react';
import { useTheme } from 'next-themes';
import { NotificationsType } from '@/types/notification';
import { Ticket } from '@/types/Ticket';
import { cn } from '@/lib/utils';
import { useIsMobile } from '@/hooks/use-mobile';
import { useScrollPosition } from '@/hooks/use-scroll-position';
import { getNotifications } from '@/services/notification.services';
import { getTickets } from '@/services/ticket.services';
import { Button } from '@/components/ui/button';
import {
  Sheet,
  SheetBody,
  SheetContent,
  SheetHeader,
  SheetTrigger,
} from '@/components/ui/sheet';
import { Container } from '@/components/common/container';
import { TicketList } from '@/components/partials/topbar/chat-sheet/TicketList';
import { NotificationsSheet } from '@/components/partials/topbar/notifications-sheet';
import { ThemeToggle } from '@/components/partials/topbar/theme-toggle';
import { WrapperLangDropdownMenu } from '@/components/partials/topbar/warpper-lang-dropdown';
import { WrapperUserDropdownMenu } from '@/components/partials/topbar/wrapper-user-dropdown-menu';
import { Breadcrumb } from './breadcrumb';
import { MegaMenu } from './mega-menu';
import { SidebarMenu } from './sidebar-menu';

export function Header({ logo }: { logo: Logo }) {
  const [mount, setMount] = useState(false);
  const [isSidebarSheetOpen, setIsSidebarSheetOpen] = useState(false);
  const { theme } = useTheme();

  const { data, isPending } = useQuery<NotificationsType>({
    queryKey: ['notifications'],
    queryFn: () => getNotifications(1, '&section=normal'),
  });

  const { data: tickets, isPending: pendingTickets } = useQuery({
    queryKey: ['tickets'],
    queryFn: () => getTickets(),
  });

  const unReadNotif = data?.data?.filter((notif) => notif.read === false);

  const hasTickets = tickets?.data.some((ticket: Ticket) =>
    ['new', 'answered'].includes(ticket.status),
  );

  const pathname = usePathname();
  const mobileMode = useIsMobile();

  const scrollPosition = useScrollPosition();
  const headerSticky: boolean = scrollPosition > 0;

  // Close sheet when route changes
  useEffect(() => {
    setIsSidebarSheetOpen(false);
  }, [pathname]);

  useEffect(() => {
    setMount(true);
  }, []);
  if (!mount) return;

  return (
    <header
      className={cn(
        'header fixed top-0 z-10 start-0 flex items-stretch shrink-0 border-b border-transparent bg-background/75 backdrop-blur-xl end-0 ps-[var(--removed-body-scroll-bar-size,0px)]',
        headerSticky && 'border-b border-border',
      )}
    >
      <Container className="flex justify-between items-stretch">
        {/* HeaderLogo */}
        <div className="flex lg:hidden items-center md:gap-2.5">
          <Link href="/" className="shrink-0">
            {theme === 'dark' ? (
              <img
                src={logo?.darkLogo}
                className="h-[25px] w-full hidden md:block"
                alt="logo-dark"
              />
            ) : (
              <img
                src={logo?.lightLogo}
                className="h-[25px] w-full hidden md:block"
                alt="logo-light"
              />
            )}
          </Link>
          <div className="flex items-center">
            {mobileMode && (
              <Sheet
                open={isSidebarSheetOpen}
                onOpenChange={setIsSidebarSheetOpen}
              >
                <SheetTrigger asChild>
                  <Menu className="text-muted-foreground/70 size-7" />
                </SheetTrigger>
                <SheetContent
                  className="p-0 gap-0 w-[275px]"
                  side="left"
                  close={false}
                >
                  <SheetHeader className="p-0 space-y-0" />
                  <SheetBody className="p-0 overflow-y-auto">
                    <div className="p-3 flex border-b">
                      <img
                        src={
                          theme === 'light' ? logo?.lightLogo : logo?.darkLogo
                        }
                        className="h-[20px] w-[110px]"
                        alt="mini-logo"
                      />
                    </div>
                    <SidebarMenu />
                  </SheetBody>
                </SheetContent>
              </Sheet>
            )}
          </div>
        </div>

        {/* Main Content (MegaMenu or Breadcrumbs) */}
        {pathname.startsWith('/account') ? (
          <Breadcrumb />
        ) : (
          !mobileMode && <MegaMenu />
        )}

        {/* HeaderTopbar */}
        <div className="flex items-center gap-x-3">
          {pathname.startsWith('/store-client') ? (
            ''
          ) : (
            <>
              <ThemeToggle />
              <NotificationsSheet
                isPending={isPending}
                unReadNotifications={unReadNotif}
                trigger={
                  <Button
                    variant="ghost"
                    mode="icon"
                    shape="circle"
                    className="size-10 hover:bg-primary/10 hover:[&_svg]:text-primary pt-2"
                  >
                    <div className="relative">
                      <i className="ki-outline ki-notification-on text-2xl md:text-lg"></i>
                      <div
                        className={`items-center top-0 rounded-full border-2 border-background 
                            bg-green-600 size-2.5 absolute hidden ${unReadNotif?.length && '!flex'}`}
                      ></div>
                    </div>
                  </Button>
                }
              />

              <TicketList
                tickets={tickets?.data}
                isPending={pendingTickets}
                trigger={
                  <Button
                    variant="ghost"
                    mode="icon"
                    shape="circle"
                    className="size-10 hover:bg-primary/10 hover:[&_svg]:text-primary pt-2"
                  >
                    <div className="relative">
                      <i className="ki-outline ki-messages text-2xl md:text-lg"></i>
                      <div
                        className={`items-center top-0 rounded-full border-2 border-background 
                            bg-green-600 size-2.5 absolute hidden ${hasTickets && '!flex'}`}
                      ></div>
                    </div>
                  </Button>
                }
              />

              <WrapperLangDropdownMenu />
              <WrapperUserDropdownMenu />
            </>
          )}
        </div>
      </Container>
    </header>
  );
}
