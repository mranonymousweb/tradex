'use client';

import Link from 'next/link';
import { LucideIcon, ShieldCheck } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toAbsoluteUrl } from '@/lib/helpers';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { HexagonBadge } from '@/components/partials/common/hexagon-badge';
import { SwitchToTP } from './dialog-enable-two-factor';

interface IAuthTwoFactorItem {
  icon: LucideIcon;
  title: string;
  description: string;
  checkbox: boolean;
}
type IAuthTwoFactorItems = Array<IAuthTwoFactorItem>;

interface IAuthSocialSignInBlock {
  logo: string;
  title: string;
  link: string;
}
type IAuthSocialSignInBlocks = Array<IAuthSocialSignInBlock>;

const AuthTwoFactor = ({ totpUser }: { totpUser: boolean }) => {
  const t = useTranslations('profile');

  const blocks: IAuthSocialSignInBlocks = [
    {
      logo: 'apple-black.svg',
      title: 'App store',
      link: 'https://apps.apple.com/us/app/google-authenticator/id388497605',
    },
    {
      logo: 'google-play-store.svg',
      title: 'Google play',
      link: 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&pcampaignid=web_share',
    },
  ];

  const items: IAuthTwoFactorItems = [
    {
      icon: ShieldCheck,
      title: t('totp'),
      description: t('description_totp'),
      checkbox: false,
    },
  ];

  const renderItem = (item: IAuthTwoFactorItem, index: number) => {
    return (
      <div
        key={index}
        className="flex items-center justify-between flex-wrap border border-border rounded-xl gap-2 px-3.5 py-2.5"
      >
        <div className="flex items-center flex-wrap gap-3.5">
          <div className="flex items-center">
            <HexagonBadge
              stroke="stroke-input"
              fill="fill-muted/30"
              size="size-[50px]"
              badge={<item.icon className="text-xl text-muted-foreground" />}
            />
          </div>
          <div className="flex flex-col gap-px">
            <Link
              href="#"
              className="text-sm font-medium text-mono hover:text-primary-active"
            >
              {item.title}
            </Link>
            <span className="text-sm font-medium text-secondary-foreground">
              {item.description}
            </span>
          </div>
        </div>
        <div className="flex items-center gap-2 lg:gap-6">
          <SwitchToTP totpUser={totpUser} />
        </div>
      </div>
    );
  };

  const renderBlock = (block: IAuthSocialSignInBlock, index: number) => {
    return (
      <Link key={index} href={block.link} target="_blank">
        <Button variant="outline">
          <img
            src={toAbsoluteUrl(`/media/brand-logos/${block.logo}`)}
            className="size-5"
            alt="image"
          />
          {block.title}
        </Button>
      </Link>
    );
  };

  return (
    <Card>
      <CardHeader id="auth_two_factor">
        <CardTitle>{t('google_auth')}</CardTitle>
      </CardHeader>
      <CardContent>
        <div className="grid gap-5 mb-7">
          {items.map((item, index) => {
            return renderItem(item, index);
          })}
        </div>
        <div className="flex flex-col gap-0.5 mb-5">
          <div className="text-base font-medium text-mono">
            Google Authenticator
          </div>
          <div className="text-sm text-foreground">
            {t('description_download_app')}
          </div>
        </div>
        <div className="flex items-center flex-wrap gap-2.5 mb-7.5">
          {blocks.map((block, index) => {
            return renderBlock(block, index);
          })}
        </div>
      </CardContent>
    </Card>
  );
};

export { AuthTwoFactor, type IAuthTwoFactorItem, type IAuthTwoFactorItems };
