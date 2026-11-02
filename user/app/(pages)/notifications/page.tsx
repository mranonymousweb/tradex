import { Fragment } from 'react';
import Link from 'next/link';
import { getTranslations } from 'next-intl/server';
import { createPageMetadata } from '@/lib/page-metadata';
import { Button } from '@/components/ui/button';
import { Container } from '@/components/common/container';
import {
  Toolbar,
  ToolbarActions,
  ToolbarHeading,
} from '@/components/common/toolbar';
import {
  ToolbarDescription,
  ToolbarPageTitle,
} from '@/components/partials/common/toolbar';
import { Notifications } from './components/notifications';

export async function generateMetadata() {
  return createPageMetadata('/notifications');
}

export default async function Page() {
  const t = await getTranslations('notification');

  return (
    <Fragment>
      <Container>
        <Toolbar>
          <ToolbarHeading>
            <ToolbarPageTitle />
            <ToolbarDescription>{t('description_header')}</ToolbarDescription>
          </ToolbarHeading>
          <ToolbarActions>
            <Button variant="outline" asChild>
              <Link href="/">{t('back')}</Link>
            </Button>
          </ToolbarActions>
        </Toolbar>
      </Container>
      <Container>
        <Notifications />
      </Container>
    </Fragment>
  );
}
