import { Bell, Headset, ShieldCheck, UserCheck } from 'lucide-react';
import type { LandingCopy } from '../_lib/copy';

const icons = [ShieldCheck, UserCheck, Bell, Headset];

export function Trust({ t }: { t: LandingCopy['trust'] }) {
  return (
    <div className="grid items-center gap-12 lg:grid-cols-[1fr_1.1fr]">
      <div>
        <h2 className="lp-h2">{t.title}</h2>
        <p className="lp-lead mt-4 max-w-[46ch]">{t.sub}</p>
        <div className="lp-shield mt-10 hidden lg:block" aria-hidden>
          <span />
          <span />
          <span />
          <ShieldCheck className="size-10" />
        </div>
      </div>
      <ul className="grid gap-3 sm:grid-cols-2">
        {t.items.map((it, i) => {
          const Icon = icons[i];
          return (
            <li key={it.t} className="lp-trust-item">
              <span className="lp-icon">
                <Icon className="size-5" />
              </span>
              <h3>{it.t}</h3>
              <p>{it.d}</p>
            </li>
          );
        })}
      </ul>
    </div>
  );
}
