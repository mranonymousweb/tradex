import { ReactNode } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { CreditCard, IdCard, ShieldCheck, User } from 'lucide-react';
import { useLocale, useTranslations } from 'next-intl';
import { User as UserType } from '@/types/auth';
import { Badge } from '@/components/ui/badge';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Logout } from './logout';

export function UserDropdownMenu({
  trigger,
  user,
}: {
  trigger: ReactNode;
  user: UserType;
}) {
  const t = useTranslations('header.user');
  const local = useLocale();
  const dir = local !== 'fa' ? 'rtl' : 'ltr';

  return (
    <DropdownMenu dir={dir}>
      <DropdownMenuTrigger asChild>{trigger}</DropdownMenuTrigger>
      <DropdownMenuContent className="w-72 md:w-64">
        {/* Header */}

        <div className="flex items-center justify-end gap-x-1">
          <div className="flex flex-col items-end">
            <span className="ltr text-right text-sm text-muted-foreground w-48 overflow-hidden whitespace-nowrap text-ellipsis block">
              {user?.email}
            </span>
            <Badge
              variant="primary"
              appearance="outline"
              size="sm"
              className="shrink-0"
            >
              {`ID : ${user?.prefix_id} - ${user?.id}`}
            </Badge>
          </div>
          <Image
            width={36}
            height={36}
            className="w-9 h-9 rounded-full border border-border object-cover"
            src={user?.avatar}
            alt="User avatar"
          />
        </div>

        <DropdownMenuSeparator />

        {/* Menu Items */}
        <DropdownMenuItem asChild>
          <Link
            href="/settings-account"
            className="flex items-center justify-end gap-2 text-base md:text-base pb-2 md:pb-0"
          >
            {t('title_link_1')}
            <User className="size-5 md:size-4" />
          </Link>
        </DropdownMenuItem>
        <DropdownMenuItem asChild>
          <Link
            href="/authentication"
            className="flex items-center justify-end gap-2 text-base md:text-base pb-2 md:pb-0"
          >
            {t('title_link_2')}
            <IdCard className="size-5 md:size-4" />
          </Link>
        </DropdownMenuItem>
        <DropdownMenuItem asChild>
          <Link
            href="/user-bank"
            className="flex items-center justify-end gap-2 text-base md:text-base pb-2 md:pb-0"
          >
            {t('title_link_3')}
            <CreditCard className="size-5 md:size-4" />
          </Link>
        </DropdownMenuItem>
        <DropdownMenuItem asChild>
          <Link
            href="/settings-account#auth_password"
            className="flex items-center justify-end gap-2 text-base md:text-base pb-2 md:pb-0"
          >
            {t('title_link_4')}
            <ShieldCheck className="size-5 md:size-4" />
          </Link>
        </DropdownMenuItem>
        <DropdownMenuSeparator />

        {/* Footer */}
        <div className="px-2 mt-1">
          <Logout />
        </div>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
