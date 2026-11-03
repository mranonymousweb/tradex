import type { LandingCopy } from '../_lib/copy';
import { InView } from './in-view';

export function Steps({ t, lang }: { t: LandingCopy['steps']; lang: 'fa' | 'en' }) {
  const digits = lang === 'fa' ? ['۱', '۲', '۳', '۴'] : ['1', '2', '3', '4'];
  return (
    <InView className="lp-steps">
      <ol>
        {t.items.map((s, i) => (
          <li key={s.t} style={{ ['--i' as string]: i }}>
            <span className="lp-step-dot">{digits[i]}</span>
            <h3>{s.t}</h3>
            <p>{s.d}</p>
          </li>
        ))}
      </ol>
    </InView>
  );
}
