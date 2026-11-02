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
import { ToolbarPageTitle } from '@/components/partials/common/toolbar';
import { Logs } from '../../../components/logs-table';

export async function generateMetadata({
  params,
}: {
  params: Promise<{ id: string; userId: string }>;
}) {
  const { id, userId } = await params;
  return createPageMetadata(`/marketing/${id}/details/${userId}`);
}

export default async function Page({
  params,
}: {
  params: Promise<{ id: string; userId: string }>;
}) {
  const t = await getTranslations('marketing');
  const { id, userId } = await params;

  return (
    <Fragment>
      <Container>
        <Toolbar>
          <ToolbarHeading>
            <ToolbarPageTitle text={t('user_transactions')} />
          </ToolbarHeading>
          <ToolbarActions>
            <Button variant="outline" asChild>
              <Link href={`/marketing/${id}`}>{t('back')}</Link>
            </Button>
          </ToolbarActions>
        </Toolbar>
      </Container>
      <Container>
        <Logs id={userId} />
      </Container>
    </Fragment>
  );
}
