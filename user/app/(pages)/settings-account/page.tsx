import { Fragment, Suspense } from 'react';
import Link from 'next/link';
import { getTranslations } from 'next-intl/server';
import { createPageMetadata } from '@/lib/page-metadata';
import { getProfileInfo } from '@/services/profile.services';
import { devicesLog } from '@/services/setting-account.services';
import { Button } from '@/components/ui/button';
import { Container } from '@/components/common/container';
import { ContentLoader } from '@/components/common/content-loader';
import {
  Toolbar,
  ToolbarActions,
  ToolbarHeading,
} from '@/components/common/toolbar';
import { ToolbarPageTitle } from '@/components/partials/common/toolbar';
import { AccountSettingsSidebarContent } from './content';

export async function generateMetadata() {
  const t = await getTranslations('profile');
  return createPageMetadata('/settings-account', { title: t('title') });
}

export default async function Account() {
  const t = await getTranslations('profile');
  const user = await getProfileInfo();
  const devices = await devicesLog();
  if (!user) return <ContentLoader />;

  return (
    <Fragment>
      <Container>
        <Toolbar>
          <ToolbarHeading>
            <ToolbarPageTitle text={t('title')} />
          </ToolbarHeading>
          <ToolbarActions>
            <Button variant="outline" asChild>
              <Link href="/">{t('back')}</Link>
            </Button>
          </ToolbarActions>
        </Toolbar>
      </Container>
      <Container>
        <Suspense>
          <AccountSettingsSidebarContent user={user} devices={devices} />
        </Suspense>
      </Container>
    </Fragment>
  );
}
