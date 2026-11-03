import { Fragment } from 'react';
import Link from 'next/link';
import { getTranslations } from 'next-intl/server';
import { createPageMetadata } from '@/lib/page-metadata';
import { banks, Cardcurrency } from '@/services/banks.services';
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
import FormAdd from './components/add-bank';
import { Table } from './components/list-banks';

export async function generateMetadata() {
  return createPageMetadata('/user-bank');
}

export default async function Page() {
  const t = await getTranslations('bank');
  const IranBanks = await banks();
  const bankCuurency = await Cardcurrency();

  return (
    <Fragment>
      <Container>
        <Toolbar>
          <ToolbarHeading>
            <ToolbarPageTitle text={t('banks')} />
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
        <FormAdd cardCurrencies={bankCuurency} banks={IranBanks} />
        <Table />
      </Container>
    </Fragment>
  );
}
