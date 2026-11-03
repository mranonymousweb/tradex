import { Fragment } from 'react';
import Link from 'next/link';
import { getTranslations } from 'next-intl/server';
import { createPageMetadata } from '@/lib/page-metadata';
import { metaData } from '@/services/metaData.services';
import { CurrenciesStock } from '@/services/stock.services';
import { Button } from '@/components/ui/button';
import { Container } from '@/components/common/container';
import {
  Toolbar,
  ToolbarActions,
  ToolbarHeading,
} from '@/components/common/toolbar';
import { ToolbarPageTitle } from '@/components/partials/common/toolbar';
import { ContentStock } from './content';

export async function generateMetadata() {
  return createPageMetadata('/stock');
}

export default async function Page() {
  const t = await getTranslations('stock');
  const currencies = await CurrenciesStock();
  const meta = await metaData();

  return (
    <Fragment>
      <Container>
        <Toolbar>
          <ToolbarHeading>
            <ToolbarPageTitle />
          </ToolbarHeading>
          <ToolbarActions>
            <Button variant="outline" asChild>
              <Link href="/">{t('back')}</Link>
            </Button>
          </ToolbarActions>
        </Toolbar>
      </Container>
      <Container>
        <ContentStock currencies={currencies} metaLink={meta?.url?.user_rule} />
      </Container>
    </Fragment>
  );
}
