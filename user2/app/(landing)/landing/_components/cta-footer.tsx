import Link from 'next/link';
import type { LandingCopy } from '../_lib/copy';

export function Cta({ t }: { t: LandingCopy['cta'] }) {
  return (
    <div className="lp-cta">
      <h2 className="lp-h2 max-w-[22ch]">{t.title}</h2>
      <p className="lp-lead mt-4 max-w-[48ch]">{t.sub}</p>
      <div className="mt-8 flex flex-wrap gap-3">
        <Link href="/signup" className="lp-btn">
          {t.primary}
        </Link>
        <Link href="/signin" className="lp-btn lp-btn-ghost">
          {t.secondary}
        </Link>
      </div>
    </div>
  );
}

type FooterProps = {
  brand: string;
  t: LandingCopy['footer'];
  flags: { spot: boolean; futures: boolean };
};

export function Footer({ brand, t, flags }: FooterProps) {
  const col = (title: string, links: { href: string; label: string }[]) => (
    <div>
      <h4 className="mb-3 text-sm font-semibold">{title}</h4>
      <ul className="space-y-2">
        {links.map((l) => (
          <li key={l.href}>
            <Link href={l.href} className="text-sm text-muted-foreground transition-colors hover:text-foreground">
              {l.label}
            </Link>
          </li>
        ))}
      </ul>
    </div>
  );

  const product = [
    { href: '/markets', label: t.markets },
    ...(flags.spot ? [{ href: '/trade/spot', label: t.spot }] : []),
    ...(flags.futures ? [{ href: '/trade/futures', label: t.futures }] : []),
    { href: '/swap', label: t.swap },
  ];

  return (
    <footer className="border-t border-border">
      <div className="lp-container grid gap-10 py-14 md:grid-cols-[1.4fr_1fr_1fr_1fr]">
        <div>
          <div className="flex items-center gap-2">
            <span className="lp-logo-mark" aria-hidden />
            <span className="text-lg font-extrabold tracking-tight">{brand}</span>
          </div>
        </div>
        {col(t.product, product)}
        {col(t.account, [
          { href: '/signin', label: t.signin },
          { href: '/signup', label: t.signup },
        ])}
        {col(t.help, [{ href: '/ticket/add', label: t.ticket }])}
      </div>
      <div className="lp-container border-t border-border py-5 text-xs text-muted-foreground">
        © {new Date().getFullYear()} {brand}. {t.rights}
      </div>
    </footer>
  );
}
