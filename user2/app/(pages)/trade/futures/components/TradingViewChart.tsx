/* eslint-disable @typescript-eslint/no-explicit-any */
'use client';

import React, { useContext, useEffect, useRef } from 'react';
import { MainSpotContext } from '@/store/trade-spot';
import { useLocale } from 'next-intl';
import { useTheme } from 'next-themes';
import { createTradingViewLocalStorageAdapter } from '@/utils/tradingViewLocalStorageAdapter';
import { createTradingViewCustomFormatters } from '@/utils/tradingViewPriceFormatter';
import { FuturesContext } from '@/store/futures';

const CHART_BRAND_NAME = process.env.NEXT_PUBLIC_APP_NAME || 'ARYA';

const getChartTimezone = (locale: string) =>
    locale === 'fa' ? 'Asia/Tehran' : 'Europe/London';

const loadScriptOnce = (src: string) =>
    new Promise<void>((resolve, reject) => {
        if (document.querySelector(`script[src="${src}"]`)) {
            resolve();
            return;
        }

        const s = document.createElement('script');
        s.src = src;
        s.onload = () => resolve();
        s.onerror = () => reject(new Error(`Failed to load script: ${src}`));
        document.head.appendChild(s);
    });

export default function TradingViewChart() {
    const { pair } = useContext(FuturesContext);
    const { resolvedTheme } = useTheme();
    const locale = useLocale();
    const widgetRef = useRef<any>(null);
    const symbolCoin = pair.replace('_', '-').toUpperCase();

    useEffect(() => {
        if (!resolvedTheme) return;

        let mounted = true;
        const chartTheme = resolvedTheme === 'light' ? 'light' : 'dark';
        const chartTimezone = getChartTimezone(locale);

        const overridesColor =
            chartTheme === 'dark'
                ? {
                    'paneProperties.backgroundType': 'gradient',
                    'paneProperties.backgroundGradientStartColor': '#111111',
                    'paneProperties.backgroundGradientEndColor': '#0b0809',
                    'paneProperties.backgroundGradientDegree': 90,
                    'paneProperties.scalesProperties': '#111111',
                    'scalesProperties.textColor': '#848e9c',
                }
                : undefined;

        const applyChartTheme = (widget: any) => {
            widget.changeTheme(chartTheme === 'dark' ? 'Dark' : 'Light');
            if (overridesColor) {
                widget.applyOverrides(overridesColor);
            }
        };

        const applyChartBranding = (widget: any) => {
            try {
                applyChartTheme(widget);
                widget.activeChart().getTimezoneApi().setTimezone(chartTimezone);

                const watermark = widget.watermark();
                watermark.setContentProvider(() => [
                    {
                        text: CHART_BRAND_NAME,
                        fontSize: 72,
                        lineHeight: 88,
                        vertOffset: 0,
                    },
                ]);
                watermark.visibility().setValue(true);
                watermark.color().setValue(
                    chartTheme === 'dark'
                        ? 'rgba(132, 142, 156, 0.12)'
                        : 'rgba(19, 23, 34, 0.08)',
                );
            } catch (err) {
                console.error('[TradingViewChart] failed to apply chart branding', err);
            }
        };

        const init = async () => {
            try {
                await loadScriptOnce(
                    '/chart/charting_library/charting_library.standalone.js',
                );
                await loadScriptOnce('/chart/datafeeds/udf/bundle/bundle.js');
            } catch (err) {
                console.error('[TradingViewChart] failed to load chart scripts', err);
                return;
            }

            if (!mounted || !window.TradingView) return;

            const container = document.getElementById('chartContainer');
            if (container) {
                container.innerHTML = '';
            }

            const baseDatafeed = new Datafeeds.UDFCompatibleDatafeed(
                `${process.env.NEXT_PUBLIC_API_BACKEND_URL}/api/v1/chart/future`,
            );

            widgetRef.current = new window.TradingView.widget({
                container: 'chartContainer',
                autosize: true,
                symbol: symbolCoin,
                theme: chartTheme,
                locale: (locale === 'fa' ? 'fa' : 'en') as 'en',
                timezone: chartTimezone,
                overrides: overridesColor,
                header_widget_buttons_mode: 'fullsize',
                enabled_features: [
                    'side_toolbar_in_fullscreen_mode',
                    'header_in_fullscreen_mode',
                ],
                disabled_features: [
                    'control_bar',
                    'header_symbol_search',
                    'symbol_search_hot_key',
                    'use_localstorage_for_settings',
                    'save_chart_properties_to_local_storage',
                    'link_to_tradingview' as any,
                    'widget_logo' as any,
                ],
                save_load_adapter: createTradingViewLocalStorageAdapter(),
                load_last_chart: true,
                auto_save_delay: 5,
                library_path: '/chart/charting_library/',
                interval: "1D" as any,
                datafeed: baseDatafeed,
                custom_formatters: createTradingViewCustomFormatters(),
            });

            widgetRef.current.onChartReady(() => {
                if (mounted) {
                    applyChartBranding(widgetRef.current);
                }
            });
        };

        init();

        return () => {
            mounted = false;
            widgetRef.current?.remove();
        };
    }, [symbolCoin, resolvedTheme, locale]);

    return <div id="chartContainer" style={{ height: '100%', width: '100%' }} />;
}
