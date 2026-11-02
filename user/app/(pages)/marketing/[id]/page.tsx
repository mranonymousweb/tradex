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
import { Subset } from '../components/subset-table';

export async function generateMetadata({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;
  return createPageMetadata(`/marketing/${id}`);
}

export default async function Page({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const t = await getTranslations('marketing');
  const { id } = await params;

  return (
    <Fragment>
      <Container>
        <Toolbar>
          <ToolbarHeading>
            <ToolbarPageTitle text={t('subsets')} />
            <ToolbarDescription>{t('user_subset')}</ToolbarDescription>
          </ToolbarHeading>
          <ToolbarActions>
            <Button variant="outline" asChild>
              <Link href="/marketing">{t('back')}</Link>
            </Button>
          </ToolbarActions>
        </Toolbar>
      </Container>
      <Container>
        <Subset id={id} />
      </Container>
    </Fragment>
  );
}
