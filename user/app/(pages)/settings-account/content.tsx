'use client';

import { useEffect, useRef, useState } from 'react';
import { User } from '@/types/auth';
import { Device } from '@/types/profile';
import { cn } from '@/lib/utils';
import { useIsMobile } from '@/hooks/use-mobile';
import { useScrollPosition } from '@/hooks/use-scroll-position';
import { Scrollspy } from '@/components/ui/scrollspy';
import { AccountSettingsSidebar } from './account-basic-sidebar';
import {
  AuthEmail,
  AuthPassword,
  AuthTwoFactor,
  BasicSettings,
  Devices,
} from './components';

export function AccountSettingsSidebarContent({
  user,
  devices,
}: {
  user: User;
  devices: Device[];
}) {
  const isMobile = useIsMobile();
  const [sidebarSticky, setSidebarSticky] = useState(false);
  const parentRef = useRef<HTMLElement | Document | undefined>(undefined);
  const scrollPosition = useScrollPosition({ targetRef: parentRef });

  // Effect to update parentRef after the component mounts
  useEffect(() => {
    const scrollableElement =
      document.getElementById('scrollable_content') ?? document;
    parentRef.current = scrollableElement;
  }, []); // Run only once on component mount

  // Handle scroll position and sidebar stickiness
  useEffect(() => {
    setSidebarSticky(scrollPosition > 100);
  }, [scrollPosition]);

  return (
    <div className="flex grow gap-5 lg:gap-7.5">
      {!isMobile && (
        <div className="w-[230px] shrink-0">
          <div
            className={cn(
              'w-[230px]',
              sidebarSticky &&
                `fixed z-10 start-auto top-[calc(var(--header-height)+1rem)]`,
            )}
          >
            <Scrollspy offset={100} targetRef={parentRef}>
              <AccountSettingsSidebar />
            </Scrollspy>
          </div>
        </div>
      )}
      <div className="flex flex-col items-stretch grow gap-5 lg:gap-7.5">
        <BasicSettings user={user} />
        <AuthPassword />
        <AuthEmail />
        <AuthTwoFactor totpUser={user?.google_2fa_is_enabled} />
        <Devices devices={devices} />
        {/*<ExternalServicesManageApi title={''} switch={false} />*/}
      </div>
    </div>
  );
}
