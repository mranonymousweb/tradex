import Link from 'next/link';
import type { Metadata } from 'next';
import { cookies } from 'next/headers';
import { getLocale } from 'next-intl/server';
import { metaData } from '@/services/metaData.services';
import { rtlLocales } from '@/utils/rtlLocales';
import { Features } from './_components/features';
import { HeroChart } from './_components/hero-chart';
import { LandingNav } from './_components/nav';
import { MarketsTable } from './_components/markets-table';
import { Steps } from './_components/steps';
import { Ticker } from './_components/ticker';
import { Trust } from './_components/trust';
import { Cta, Footer } from './_components/cta-footer';
import { copy, pickLang } from './_lib/copy';

function brandFrom(title?: string | null) {
  const first = title?.split(/[|\-–—]/)[0]?.trim();
  return first || process.env.NEXT_PUBLIC_APP_NAME || 'Exchange';
}

export async function generateMetadata(): Promise<Metadata> {
  const data = await metaData();
  const title = data?.meta?.meta_data_title;
  return { title: title ? { absolute: title } : undefined };
}

export default async function LandingPage() {
  const locale = await getLocale();
  const lang = pickLang(locale);
  const t = copy[lang];
  const rtl = rtlLocales.includes(locale);

  const loggedIn = Boolean((await cookies()).get('user_session')?.value);
  const data = await metaData();
  const brand = brandFrom(data?.meta?.meta_data_title);
  const logo: string | undefined = data?.meta?.meta_data_dark_logo || undefined;

  const flags = {
    spot: process.env.NEXT_PUBLIC_ENABLE_SPOT === 'true',
    futures: process.env.NEXT_PUBLIC_ENABLE_FUTURES === 'true',
  };
  const tradeHref = flags.spot ? '/trade/spot' : '/markets';

  return (
    <div className="dark lp-root" dir={rtl ? 'rtl' : 'ltr'}>
      <LandingNav
        brand={brand}
        logo={logo}
        signin={t.nav.signin}
        signup={t.nav.signup}
        menuLabel={t.nav.menu}
        panel={t.nav.panel}
        loggedIn={loggedIn}
        links={[
          { href: '#markets', label: t.nav.markets },
          { href: '#features', label: t.nav.features },
          { href: '#steps', label: t.nav.steps },
          { href: '#security', label: t.nav.security },
        ]}
      />

      <main>
        {/* Hero */}
        <section className="lp-hero">
          <div className="lp-hero-bg" aria-hidden />
          <div className="lp-container lp-hero-grid">
            <div className="lp-hero-copy">
              <ul className="lp-chips">
                {t.hero.chips.map((c) => (
                  <li key={c}>{c}</li>
                ))}
              </ul>
              <h1 className="lp-h1">
                <span>{t.hero.title1}</span>
                <span className="text-gradient-brand">{t.hero.title2}</span>
              </h1>
              <p className="lp-lead mt-6 max-w-[46ch]">{t.hero.sub}</p>
              <div className="mt-9 flex flex-wrap gap-3">
                <Link href={loggedIn ? '/' : '/signup'} className="lp-btn">
                  {loggedIn ? t.nav.panel : t.hero.primary}
                </Link>
                <Link href="/markets" className="lp-btn lp-btn-ghost">
                  {t.hero.secondary}
                </Link>
              </div>
            </div>

            <div className="lp-hero-chart">
              <HeroChart
                pair="BTC/USDT"
                liveLabel={t.hero.live}
                buyLabel={t.hero.buy}
                sellLabel={t.hero.sell}
                demoNote={t.hero.demo}
                tradeHref={tradeHref}
              />
            </div>
          </div>
        </section>

        <Ticker />

        {/* Markets */}
        <section id="markets" className="lp-section">
          <div className="lp-container">
            <div className="lp-head">
              <h2 className="lp-h2">{t.markets.title}</h2>
              <p className="lp-lead">{t.markets.sub}</p>
            </div>
            <MarketsTable labels={t.markets} lang={lang} tradeHref={tradeHref} />
            <div className="mt-4 flex flex-wrap items-center justify-between gap-3">
              <p className="text-xs text-muted-foreground">{t.markets.sample}</p>
              <Link href="/markets" className="lp-link-btn">
                {t.markets.all}
              </Link>
            </div>
          </div>
        </section>

        {/* Features */}
        <section id="features" className="lp-section">
          <div className="lp-container">
            <div className="lp-head">
              <h2 className="lp-h2">{t.features.title}</h2>
              <p className="lp-lead">{t.features.sub}</p>
            </div>
            <Features t={t.features} flags={flags} rtl={rtl} />
          </div>
        </section>

        {/* Steps */}
        <section id="steps" className="lp-section">
          <div className="lp-container">
            <div className="lp-head">
              <h2 className="lp-h2">{t.steps.title}</h2>
              <p className="lp-lead">{t.steps.sub}</p>
            </div>
            <Steps t={t.steps} lang={lang} />
          </div>
        </section>

        {/* Security */}
        <section id="security" className="lp-section">
          <div className="lp-container">
            <Trust t={t.trust} />
          </div>
        </section>

        {/* CTA */}
        <section className="lp-section pb-24">
          <div className="lp-container">
            <Cta t={t.cta} />
          </div>
        </section>
      </main>

      <Footer brand={brand} t={t.footer} flags={flags} />
    </div>
  );
}
