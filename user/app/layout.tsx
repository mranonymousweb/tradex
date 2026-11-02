import { ReactNode } from 'react';
import { cn } from '@/lib/utils';
import { SettingsProvider } from '@/providers/settings-provider';
import { TooltipsProvider } from '@/providers/tooltips-provider';
import { Toaster } from '@/components/ui/sonner';
import '@/css/styles.css';
import '@/components/keenicons/assets/styles.css';
import { Metadata } from 'next';
import { NextIntlClientProvider } from 'next-intl';
import { getLocale } from 'next-intl/server';
import { QueryProvider } from '@/providers/query-provider';
import { ThemeProvider } from '@/providers/theme-provider';
import { metaData } from '@/services/metaData.services';
import { Geist, iranyekan } from './models/fonts';
import { rtlLocales } from '@/utils/rtlLocales';
import Script from 'next/script';

export async function generateMetadata(): Promise<Metadata> {
    const data = await metaData();
    const favicon = data?.meta?.meta_data_favicon || undefined;
    const siteTitle = data?.meta?.meta_data_title ?? 'app';
    return {
        title: {
            default: siteTitle,
            template: `%s | ${siteTitle}`,
        },
        description: data?.meta?.meta_data_description ?? '',
        keywords: data?.meta?.meta_data_keywords ?? '',
        authors: [{ name: data?.meta?.meta_data_author ?? '' }],
        icons: favicon
            ? {
                icon: [
                    { media: '(prefers-color-scheme: light)', url: favicon },
                    { media: '(prefers-color-scheme: dark)', url: favicon },
                ],
            }
            : undefined,
    };
}

export default async function RootLayout({
    children,
}: {
    children: ReactNode;
}) {
    const lang = await getLocale();
    const fontStyle = lang === 'fa' ? iranyekan : Geist;
    const dir = rtlLocales.includes(lang) ? 'rtl' : 'ltr';
    const meta = await metaData();
    const footerScripts = meta?.footer_scripts || [];

    return (
        <html className="h-full scroll-smooth" dir={dir} suppressHydrationWarning>
            <body
                className={cn(
                    'antialiased flex h-full text-base text-foreground bg-background demo1 sidebar-fixed header-fixed',
                    fontStyle.className,
                )}
                suppressHydrationWarning
            >
                <NextIntlClientProvider>
                    <QueryProvider>
                        <SettingsProvider>
                            <ThemeProvider>
                                <TooltipsProvider>
                                    {children}
                                    <Toaster />
                                </TooltipsProvider>
                            </ThemeProvider>
                        </SettingsProvider>
                    </QueryProvider>
                </NextIntlClientProvider>
                <Script
                    id="goftino-widget"
                    strategy="afterInteractive"
                    dangerouslySetInnerHTML={{
                        __html: footerScripts[0],
                    }}
                />
                <Script
                    id="goftino-widgets"
                    strategy="afterInteractive"
                    dangerouslySetInnerHTML={{
                        __html: footerScripts[1],
                    }}
                />
            </body>
        </html>
    );
}
