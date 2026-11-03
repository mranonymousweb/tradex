import Link from 'next/link';
import { ArrowLeftRight, ChevronLeft, Layers, PiggyBank, Gauge } from 'lucide-react';
import { cn } from '@/lib/utils';
import type { LandingCopy } from '../_lib/copy';

type Flags = { spot: boolean; futures: boolean };

function Arrow({ rtl }: { rtl: boolean }) {
  return <ChevronLeft className={cn('size-4', !rtl && 'rotate-180')} aria-hidden />;
}

function BookMini({ ask, bid }: { ask: string; bid: string }) {
  const asks = [88, 64, 46, 30];
  const bids = [34, 52, 70, 92];
  return (
    <div className="lp-book" dir="ltr" aria-hidden>
      {asks.map((w, i) => (
        <div key={`a${i}`} className="lp-book-row down" style={{ ['--w' as string]: `${w}%`, ['--d' as string]: `${i * 0.35}s` }}>
          <span>{ask}</span>
          <i />
          <b className="tabular">{(98430 + (4 - i) * 6.5).toFixed(1)}</b>
        </div>
      ))}
      <div className="lp-book-mid tabular">98,420.5</div>
      {bids.map((w, i) => (
        <div key={`b${i}`} className="lp-book-row up" style={{ ['--w' as string]: `${w}%`, ['--d' as string]: `${i * 0.4 + 0.2}s` }}>
          <span>{bid}</span>
          <i />
          <b className="tabular">{(98414 - i * 6.5).toFixed(1)}</b>
        </div>
      ))}
    </div>
  );
}

export function Features({
  t,
  flags,
  rtl,
}: {
  t: LandingCopy['features'];
  flags: Flags;
  rtl: boolean;
}) {
  return (
    <div className="lp-bento">
      {flags.spot && (
        <article className="lp-card lp-span-2">
          <div className="lp-card-body">
            <span className="lp-icon">
              <Layers className="size-5" />
            </span>
            <h3>{t.spot.t}</h3>
            <p>{t.spot.d}</p>
            <Link href="/trade/spot" className="lp-link-btn mt-auto">
              {t.spot.cta} <Arrow rtl={rtl} />
            </Link>
          </div>
          <BookMini ask={t.spot.ask} bid={t.spot.bid} />
        </article>
      )}

      {flags.futures && (
        <article className="lp-card">
          <div className="lp-card-body">
            <span className="lp-icon">
              <Gauge className="size-5" />
            </span>
            <h3>{t.futures.t}</h3>
            <p>{t.futures.d}</p>
          </div>
          <div className="lp-lev" dir="ltr" aria-hidden>
            <div className="lp-lev-head">
              <span>{t.futures.lev}</span>
              <b className="tabular">PnL +12.4%</b>
            </div>
            <div className="lp-lev-track">
              <i />
              <em />
            </div>
          </div>
          <Link href="/trade/futures" className="lp-link-btn lp-card-cta">
            {t.futures.cta} <Arrow rtl={rtl} />
          </Link>
        </article>
      )}

      <article className="lp-card">
        <div className="lp-card-body">
          <span className="lp-icon">
            <ArrowLeftRight className="size-5" />
          </span>
          <h3>{t.swap.t}</h3>
          <p>{t.swap.d}</p>
        </div>
        <div className="lp-swap" dir="ltr" aria-hidden>
          <span className="lp-coin" style={{ ['--h' as string]: 35 }}>B</span>
          <ArrowLeftRight className="lp-swap-arrow size-5" />
          <span className="lp-coin" style={{ ['--h' as string]: 225 }}>E</span>
        </div>
        <Link href="/swap" className="lp-link-btn lp-card-cta">
          {t.swap.cta} <Arrow rtl={rtl} />
        </Link>
      </article>

      <article className="lp-card lp-span-2">
        <div className="lp-card-body">
          <span className="lp-icon">
            <PiggyBank className="size-5" />
          </span>
          <h3>{t.stock.t}</h3>
          <p>{t.stock.d}</p>
          <Link href="/stock" className="lp-link-btn mt-auto">
            {t.stock.cta} <Arrow rtl={rtl} />
          </Link>
        </div>
        <div className="lp-bars" dir="ltr" aria-hidden>
          {[28, 36, 33, 48, 56, 66, 84].map((h, i) => (
            <i key={i} style={{ ['--h' as string]: `${h}%`, ['--d' as string]: `${i * 0.12}s` }} />
          ))}
        </div>
      </article>
    </div>
  );
}
