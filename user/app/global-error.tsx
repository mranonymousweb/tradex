'use client';

import { Alert } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { defaultLocale, locales, type Locale } from '@/i18n/config';
import arMessages from '@/i18n/messages/ar.json';
import chMessages from '@/i18n/messages/ch.json';
import deMessages from '@/i18n/messages/de.json';
import enMessages from '@/i18n/messages/en.json';
import esMessages from '@/i18n/messages/es.json';
import faMessages from '@/i18n/messages/fa.json';
import ruMessages from '@/i18n/messages/ru.json';
import tuMessages from '@/i18n/messages/tu.json';
import { rtlLocales } from '@/utils/rtlLocales';
import { NextIntlClientProvider, useTranslations } from 'next-intl';
import { useMemo } from 'react';

const messagesByLocale = {
    fa: faMessages,
    en: enMessages,
    ar: arMessages,
    es: esMessages,
    de: deMessages,
    ch: chMessages,
    ru: ruMessages,
    tu: tuMessages,
} as const;

function getLocaleFromCookie(): Locale {
    if (typeof document === 'undefined') {
        return defaultLocale;
    }

    const match = document.cookie.match(/(?:^|; )NEXT_LOCALE=([^;]*)/);
    const value = decodeURIComponent(match?.[1] ?? '');

    return locales.includes(value as Locale) ? (value as Locale) : defaultLocale;
}

function GlobalErrorContent({ onRetry }: { onRetry: () => void }) {
    const t = useTranslations('globalError');

    return (
        <Alert
            variant="destructive"
            appearance="light"
            className="mb-3 flex flex-col justify-center w-96 mx-auto mt-32"
        >
            <div className="flex flex-col mb-4">
                <h1>{t('title')}</h1>
                <h2>{t('description')}</h2>
                <p>{t('hint')}</p>
            </div>
            <Button type="button" onClick={onRetry}>
                {t('retry')}
            </Button>
        </Alert>
    );
}

export default function GlobalError({
    error: _error,
    unstable_retry: _unstable_retry,
}: {
    error: Error & { digest?: string };
    unstable_retry: () => void;
}) {
    const locale = useMemo(() => getLocaleFromCookie(), []);
    const dir = rtlLocales.includes(locale) ? 'rtl' : 'ltr';

    return (
        <html lang={locale} dir={dir}>
            <body>
                <NextIntlClientProvider
                    locale={locale}
                    messages={messagesByLocale[locale]}
                >
                    <GlobalErrorContent onRetry={() => window.location.reload()} />
                </NextIntlClientProvider>
            </body>
        </html>
    );
}
