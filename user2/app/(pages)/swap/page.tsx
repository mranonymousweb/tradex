import { Fragment } from 'react';
import Link from 'next/link';
import { getTranslations } from 'next-intl/server';
import { createPageMetadata } from '@/lib/page-metadata';
import { getSwapCurrency } from '@/services/swap.services';
import { Button } from '@/components/ui/button';
import { Container } from '@/components/common/container';
import {
  Toolbar,
  ToolbarActions,
  ToolbarHeading,
} from '@/components/common/toolbar';
import { ToolbarPageTitle } from '@/components/partials/common/toolbar';
import { Table } from './components/Table';
import { ContentSwap } from './content';

export async function generateMetadata() {
  return createPageMetadata('/swap');
}

export default async function Page() {
  const t = await getTranslations('swap');
  const data = await getSwapCurrency();
  const intiliaCurrencies = data?.currencies?.original?.data;
  const config = data?.config;
  const totalBalance = data?.balance;

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
        <ContentSwap
          intiliaCurrencies={intiliaCurrencies}
          config={config}
          totalBalance={totalBalance}
        />
        <Table />
      </Container>
    </Fragment>
  );
}
