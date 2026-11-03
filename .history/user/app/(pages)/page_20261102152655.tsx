import { Fragment } from 'react';
import Link from 'next/link';
import { TriangleAlert } from 'lucide-react';
import { getTranslations } from 'next-intl/server';
import { createPageMetadata } from '@/lib/page-metadata';
import { Alert } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Container } from '@/components/common/container';
import {
    Toolbar,
    ToolbarActions,
    ToolbarHeading,
} from '@/components/common/toolbar';
import DashboardContent from './dashboard/components/content';

export async function generateMetadata() {
    return createPageMetadata('/');
}

export default async function Page() {
    const t = await getTranslations('dashboard');

    return (
        <Fragment>
            <Container>
                {process.env.MODE_ENV === 'demo' && (
                    <Alert variant="destructive" appearance="light" className="mb-3">
                        <div className="flex flex-col">
                            <div className="flex items-center gap-x-1 text-destructive">
                                <TriangleAlert className="text-destructive" />
                                <span className="text-base font-semibold pt-1">
                                    {t('alert.alert')}
                                </span>
                            </div>
                            <p className="mb-2 mt-1">{t('alert.description')}</p>
                            <div className="font-medium text-primary flex gap-x-1">
                                <span>{t('alert.contact')} :</span>
                                <a
                                    href="tel:+989154743239"
                                    target="_blank"
                                    className="underline underline-offset-2"
                                >
                                    09154743239
                                </a>
                            </div>
                        </div>
                    </Alert>
                )}
                <Toolbar>
                    <ToolbarHeading>
                        {/*<ToolbarPageTitle />*/}
                        <div className="flex flex-col">
                            <span className="text-sm font-semibold">{t('welcome')} 🌟</span>
                            <p className="text-muted-foreground font-medium text-sm mt-1">
                                {t('welcome_description')}
                            </p>
                        </div>
                    </ToolbarHeading>
                    <ToolbarActions>
                        <Button variant="outline" asChild>
                            <Link href="/settings-account">{t('profile')}</Link>
                        </Button>
                    </ToolbarActions>
                </Toolbar>
            </Container>
            <Container>
                <DashboardContent />
            </Container>
        </Fragment>
    );
}
