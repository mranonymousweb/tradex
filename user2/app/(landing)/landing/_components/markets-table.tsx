'use client';

import { CSSProperties, useEffect, useState } from 'react';
import Link from 'next/link';
import { cn } from '@/lib/utils';
import { SAMPLE_MARKETS, SampleMarket, fmtPrice } from '../_lib/data';
import { InView } from './in-view';

type Labels = {
  coin: string;
  price: string;
  change: string;
  chart: string;
  trade: string;
};

function Sparkline({ series, up }: { series: number[]; up: boolean }) {
  const w = 112;
  const h = 34;
  const min = Math.min(...series);
  const max = Math.max(...series);
  const rng = max - min || 1;
  const pts = series.map((v, i) => [
    (i / (series.length - 1)) * w,
    h - 3 - ((v - min) / rng) * (h - 6),
  ]);
  const d = pts.map(([x, y], i) => `${i ? 'L' : 'M'}${x.toFixed(1)} ${y.toFixed(1)}`).join(' ');
  return (
    <svg viewBox={`0 0 ${w} ${h}`} width={w} height={h} className="lp-spark" aria-hidden>
      <path
        d={d}
        fill="none"
        stroke={up ? 'var(--up)' : 'var(--down)'}
        strokeWidth="1.75"
        strokeLinecap="round"
        strokeLinejoin="round"
        pathLength={1}
      />
    </svg>
  );
}

export function MarketsTable({
  labels,
  lang,
  tradeHref,
}: {
  labels: Labels;
  lang: 'fa' | 'en';
  tradeHref: string;
}) {
  const [rows, setRows] = useState<SampleMarket[]>(SAMPLE_MARKETS);
  const [flash, setFlash] = useState<Record<string, 'up' | 'down'>>({});

  useEffect(() => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const id = window.setInterval(() => {
      if (document.hidden) return;
      const i = Math.floor(Math.random() * SAMPLE_MARKETS.length);
      const dir = Math.random() > 0.5 ? 1 : -1;
      const sym = SAMPLE_MARKETS[i].symbol;
      setRows((prev) =>
        prev.map((r, idx) =>
          idx === i
            ? {
                ...r,
                price: r.price * (1 + dir * (0.0004 + Math.random() * 0.0012)),
                change: r.change + dir * 0.02,
              }
            : r,
        ),
      );
      setFlash((f) => ({ ...f, [sym]: dir > 0 ? 'up' : 'down' }));
      window.setTimeout(() => {
        setFlash((f) => {
          const { [sym]: _omit, ...rest } = f;
          return rest;
        });
      }, 700);
    }, 1800);
    return () => window.clearInterval(id);
  }, []);

  return (
    <InView className="lp-panel overflow-hidden" threshold={0.15}>
      <div className="overflow-x-auto">
        <table className="w-full min-w-[520px] text-sm">
          <thead>
            <tr className="text-start">
              <th className="px-5 py-3 text-start">{labels.coin}</th>
              <th dir="ltr" className="px-3 py-3 text-end">{labels.price}</th>
              <th dir="ltr" className="px-3 py-3 text-end">{labels.change}</th>
              <th className="hidden px-3 py-3 text-center sm:table-cell">{labels.chart}</th>
              <th className="px-5 py-3 text-end" />
            </tr>
          </thead>
          <tbody>
            {rows.map((m, i) => {
              const up = m.change >= 0;
              return (
                <tr key={m.symbol} className="border-t border-border" style={{ '--i': i } as CSSProperties}>
                  <td className="px-5 py-3.5">
                    <div className="flex items-center gap-3">
                      <span className="lp-coin" style={{ '--h': m.hue } as CSSProperties} aria-hidden>
                        {m.symbol[0]}
                      </span>
                      <div className="leading-tight">
                        <div className="font-semibold">{lang === 'fa' ? m.nameFa : m.name}</div>
                        <div className="text-xs text-muted-foreground" dir="ltr">
                          {m.symbol}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td
                    dir="ltr"
                    className={cn(
                      'tabular px-3 py-3.5 text-end font-semibold transition-colors',
                      flash[m.symbol] === 'up' && 'lp-flash-up',
                      flash[m.symbol] === 'down' && 'lp-flash-down',
                    )}
                  >
                    {fmtPrice(m.price)}
                  </td>
                  <td dir="ltr" className={cn('tabular px-3 py-3.5 text-end font-semibold', up ? 'up' : 'down')}>
                    {up ? '+' : ''}
                    {m.change.toFixed(2)}%
                  </td>
                  <td className="hidden px-3 py-3.5 sm:table-cell">
                    <div className="flex justify-center" dir="ltr">
                      <Sparkline series={m.series} up={up} />
                    </div>
                  </td>
                  <td className="px-5 py-3.5 text-end">
                    <Link href={tradeHref} className="lp-link-btn">
                      {labels.trade}
                    </Link>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>
    </InView>
  );
}
