import { Fragment } from 'react';
import Link from 'next/link';
import { getTranslations } from 'next-intl/server';
import { createPageMetadata } from '@/lib/page-metadata';
import { currencyConversions } from '@/services/currency-conversion';
import { Button } from '@/components/ui/button';
import { Container } from '@/components/common/container';
import {
  Toolbar,
  ToolbarActions,
  ToolbarHeading,
} from '@/components/common/toolbar';
import { ToolbarPageTitle } from '@/components/partials/common/toolbar';
import { ContentConversion } from './content';

export async function generateMetadata() {
  return createPageMetadata('/conversion');
}

export default async function Page() {
  const t = await getTranslations('wallet.conversion');
  const currencies = await currencyConversions();

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
        <ContentConversion currencies={currencies} />
      </Container>
    </Fragment>
  );
}
