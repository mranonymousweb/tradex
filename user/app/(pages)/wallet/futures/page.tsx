import { Fragment } from 'react';
import Link from 'next/link';
import { getTranslations } from 'next-intl/server';
import { createPageMetadata } from '@/lib/page-metadata';
import { assetsMe } from '@/services/wallet.services';
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
import { Content } from './content';

export async function generateMetadata() {
  return createPageMetadata('/wallet/futures');
}

export default async function Page() {
  const t = await getTranslations('futures.Wallet');
  const assets = await assetsMe();
  const USDT_ASSETS = assets?.find(
    (ass: { symbol: string }) => ass.symbol === 'USDT',
  );

  return (
    <Fragment>
      <Container>
        <Toolbar>
          <ToolbarHeading>
            <ToolbarPageTitle />
            <ToolbarDescription>{t('title')}</ToolbarDescription>
          </ToolbarHeading>
          <ToolbarActions>
            <Button variant="outline" asChild>
              <Link href="/">{t('back')}</Link>
            </Button>
          </ToolbarActions>
        </Toolbar>
      </Container>
      <Container>
        <Content USDT_ASSETS={USDT_ASSETS} />
      </Container>
    </Fragment>
  );
}
