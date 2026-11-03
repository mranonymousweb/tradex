'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import { Menu, X } from 'lucide-react';
import { cn } from '@/lib/utils';

type Props = {
  brand: string;
  logo?: string;
  links: { href: string; label: string }[];
  signin: string;
  signup: string;
  menuLabel: string;
  loggedIn?: boolean;
  panel: string;
};

export function LandingNav({ brand, logo, links, signin, signup, menuLabel, loggedIn, panel }: Props) {
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  return (
    <header
      className={cn(
        'fixed inset-x-0 top-0 z-50 transition-[background-color,border-color,backdrop-filter] duration-300',
        scrolled || open
          ? 'border-b border-border bg-background/80 backdrop-blur-xl'
          : 'border-b border-transparent',
      )}
    >
      <div className="lp-container flex h-16 items-center justify-between gap-4">
        <Link href="/landing" className="flex shrink-0 items-center gap-2" aria-label={brand}>
          {logo ? (
            // eslint-disable-next-line @next/next/no-img-element
            <img src={logo} alt={brand} className="h-7 w-auto object-contain" />
          ) : (
            <>
              <span className="lp-logo-mark" aria-hidden />
              <span className="text-lg font-extrabold tracking-tight">{brand}</span>
            </>
          )}
        </Link>

        <nav className="hidden items-center gap-1 md:flex" aria-label="main">
          {links.map((l) => (
            <a
              key={l.href}
              href={l.href}
              className="rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
            >
              {l.label}
            </a>
          ))}
        </nav>

        <div className="flex items-center gap-2">
          {loggedIn ? (
            <Link href="/" className="lp-btn lp-btn-sm">
              {panel}
            </Link>
          ) : (
            <>
              <Link
                href="/signin"
                className="hidden h-9 items-center rounded-lg px-4 text-sm font-medium text-foreground transition-colors hover:bg-accent sm:inline-flex"
              >
                {signin}
              </Link>
              <Link href="/signup" className="lp-btn lp-btn-sm">
                {signup}
              </Link>
            </>
          )}
          <button
            type="button"
            className="inline-flex size-9 items-center justify-center rounded-lg border border-border md:hidden"
            aria-label={menuLabel}
            aria-expanded={open}
            onClick={() => setOpen((v) => !v)}
          >
            {open ? <X className="size-4" /> : <Menu className="size-4" />}
          </button>
        </div>
      </div>

      {open && (
        <div className="lp-container border-t border-border pb-4 md:hidden">
          <nav className="flex flex-col py-2" aria-label="mobile">
            {links.map((l) => (
              <a
                key={l.href}
                href={l.href}
                onClick={() => setOpen(false)}
                className="rounded-lg px-2 py-3 text-sm font-medium text-foreground/90"
              >
                {l.label}
              </a>
            ))}
            {!loggedIn && (
              <Link href="/signin" className="rounded-lg px-2 py-3 text-sm font-medium">
                {signin}
              </Link>
            )}
          </nav>
        </div>
      )}
    </header>
  );
}
