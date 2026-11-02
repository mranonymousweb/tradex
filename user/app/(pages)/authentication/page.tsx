import { Fragment } from 'react';
import Link from 'next/link';
import { getTranslations } from 'next-intl/server';
import { createPageMetadata } from '@/lib/page-metadata';
import { apiFetch } from '@/lib/api';
import { toAbsoluteUrl } from '@/lib/helpers';
import { metaData } from '@/services/metaData.services';
import { Button } from '@/components/ui/button';
import { DataLoading } from '@/components/ui/data-loading';
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
import { ContentAuth } from './components/content';
import { FeaturesHighlight } from './components/header';

export async function generateMetadata() {
  const t = await getTranslations('authentication');
  return createPageMetadata('/authentication', { title: t('title') });
}

export default async function Page() {
    const [data, configRes] = await Promise.all([
        metaData(),
        apiFetch('/auth/config', { method: 'GET' }).catch(() => null),
    ]);

    const t = await getTranslations('authentication');
    const config = configRes?.ok ? (await configRes.json())?.data : null;

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
                <FeaturesHighlight
                    image={
                        <Fragment>
                            <img
                                src={toAbsoluteUrl('/media/illustrations/5.svg')}
                                className="dark:hidden max-h-36"
                                alt="image"
                            />
                            <img
                                src={toAbsoluteUrl('/media/illustrations/5-dark.svg')}
                                className="light:hidden max-h-36"
                                alt="image"
                            />
                        </Fragment>
                    }
                    title={<>{t('header_title')}</>}
                    description={t('header_description')}
                    more={
                        data?.url?.user_rule
                            ? { title: t('rule'), url: data?.url?.user_rule }
                            : undefined
                    }
                    features={[
                        [t('header_option_1'), t('header_option_2')],
                        [t('header_option_3'), t('header_option_4')],
                    ]}
                />
                {config ? <ContentAuth config={config} /> : <DataLoading />}
            </Container>
        </Fragment>
    );
}
