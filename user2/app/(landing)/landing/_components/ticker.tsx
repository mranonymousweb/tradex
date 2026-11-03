import { SAMPLE_MARKETS, fmtPrice } from '../_lib/data';
import { cn } from '@/lib/utils';

/** Passive marquee of sample prices. Pure CSS, pauses on hover. */
export function Ticker() {
  const items = [...SAMPLE_MARKETS, ...SAMPLE_MARKETS];
  return (
    <div className="lp-ticker" dir="ltr" aria-hidden>
      <div className="lp-ticker-track">
        {items.map((m, i) => (
          <span key={`${m.symbol}-${i}`} className="lp-ticker-item">
            <b>{m.symbol}</b>
            <span className="tabular text-foreground/80">{fmtPrice(m.price)}</span>
            <span className={cn('tabular text-xs font-semibold', m.change >= 0 ? 'up' : 'down')}>
              {m.change >= 0 ? '+' : ''}
              {m.change.toFixed(2)}%
            </span>
          </span>
        ))}
      </div>
    </div>
  );
}
