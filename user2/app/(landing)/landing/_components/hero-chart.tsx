'use client';

import { CSSProperties, useEffect, useRef, useState } from 'react';
import Link from 'next/link';
import { cn } from '@/lib/utils';

type Candle = { o: number; h: number; l: number; c: number; v: number };

const COUNT = 56;

function mulberry32(seed: number) {
  let a = seed >>> 0;
  return () => {
    a = (a + 0x6d2b79f5) | 0;
    let t = Math.imul(a ^ (a >>> 15), 1 | a);
    t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}

/** Deterministic, so server and client render the same first price. */
function seedCandles(): Candle[] {
  const r = mulberry32(7);
  const out: Candle[] = [];
  let p = 95200;
  for (let i = 0; i < COUNT; i++) {
    const o = p;
    const c = o * (1 + (r() - 0.47) * 0.011);
    const h = Math.max(o, c) * (1 + r() * 0.0035);
    const l = Math.min(o, c) * (1 - r() * 0.0035);
    const v = 0.22 + r() * 0.6 + Math.abs(c - o) / o * 25;
    out.push({ o, h, l, c, v: Math.min(1, v) });
    p = c;
  }
  // Anchor the series so it ends at the same BTC price shown in the ticker.
  const k = 98420.5 / out[out.length - 1].c;
  return out.map((x) => ({ ...x, o: x.o * k, h: x.h * k, l: x.l * k, c: x.c * k }));
}

const fmt = (n: number) =>
  n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

type Props = {
  pair: string;
  liveLabel: string;
  buyLabel: string;
  sellLabel: string;
  demoNote: string;
  tradeHref: string;
};

export function HeroChart({ pair, liveLabel, buyLabel, sellLabel, demoNote, tradeHref }: Props) {
  const wrapRef = useRef<HTMLDivElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [stat, setStat] = useState(() => {
    const cs = seedCandles();
    const last = cs[cs.length - 1].c;
    return { price: last, pct: (last / cs[0].o - 1) * 100 };
  });

  useEffect(() => {
    const wrap = wrapRef.current;
    const canvas = canvasRef.current;
    if (!wrap || !canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const css = getComputedStyle(canvas);
    const read = (name: string, fallback: string) => css.getPropertyValue(name).trim() || fallback;
    const col = {
      up: read('--up', '#2ebd85'),
      down: read('--down', '#f6465d'),
      grid: read('--border', '#1b2540'),
      text: read('--muted-foreground', '#7f8fae'),
      brand: read('--primary', '#1b82f5'),
    };

    const candles = seedCandles();
    let target = candles[candles.length - 1].c;
    let disp = target;
    let reveal = reduced ? 1 : 0;
    let w = 0;
    let h = 0;
    let raf = 0;
    let running = false;
    let tickN = 0;
    let acc = 0;
    let last = 0;
    let startT = 0;

    const resize = () => {
      const rect = wrap.getBoundingClientRect();
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = rect.width;
      h = rect.height;
      canvas.width = Math.round(w * dpr);
      canvas.height = Math.round(h * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      draw();
    };

    function draw() {
      if (!w || !h) return;
      ctx!.clearRect(0, 0, w, h);
      const padR = 62;
      const padT = 12;
      const volH = h * 0.15;
      const plotW = w - padR;
      const plotH = h - padT - volH - 14;
      const n = candles.length;

      let hi = -Infinity;
      let lo = Infinity;
      for (const c of candles) {
        hi = Math.max(hi, c.h);
        lo = Math.min(lo, c.l);
      }
      hi = Math.max(hi, disp);
      lo = Math.min(lo, disp);
      const span = hi - lo || 1;
      hi += span * 0.1;
      lo -= span * 0.1;
      const y = (p: number) => padT + ((hi - p) / (hi - lo)) * plotH;

      // grid + axis labels
      ctx!.font = '10px ui-monospace, SFMono-Regular, Menlo, monospace';
      ctx!.textBaseline = 'middle';
      for (let i = 0; i <= 4; i++) {
        const p = lo + ((hi - lo) * i) / 4;
        const yy = y(p);
        ctx!.globalAlpha = 0.7;
        ctx!.strokeStyle = col.grid;
        ctx!.lineWidth = 1;
        ctx!.beginPath();
        ctx!.moveTo(0, Math.round(yy) + 0.5);
        ctx!.lineTo(plotW, Math.round(yy) + 0.5);
        ctx!.stroke();
        ctx!.globalAlpha = 1;
        ctx!.fillStyle = col.text;
        ctx!.fillText(fmt(p), plotW + 8, yy);
      }

      const step = plotW / n;
      const bw = Math.max(2, step * 0.58);
      const shown = reveal >= 1 ? n : Math.floor(reveal * n) + 1;

      for (let i = 0; i < Math.min(shown, n); i++) {
        const base = candles[i];
        const isLast = i === n - 1;
        const c = isLast ? { ...base, c: disp, h: Math.max(base.h, disp), l: Math.min(base.l, disp) } : base;
        const up = c.c >= c.o;
        const color = up ? col.up : col.down;
        const x = i * step + step / 2;
        const fade = 0.38 + 0.62 * Math.min(1, i / (n * 0.28));

        // volume
        ctx!.globalAlpha = 0.22 * fade;
        ctx!.fillStyle = color;
        const vh = Math.max(2, c.v * volH);
        ctx!.fillRect(x - bw / 2, h - vh - 2, bw, vh);

        // wick + body
        ctx!.globalAlpha = fade;
        ctx!.strokeStyle = color;
        ctx!.fillStyle = color;
        ctx!.lineWidth = 1;
        ctx!.beginPath();
        ctx!.moveTo(x, y(c.h));
        ctx!.lineTo(x, y(c.l));
        ctx!.stroke();
        const top = y(Math.max(c.o, c.c));
        const bh = Math.max(1.5, Math.abs(y(c.o) - y(c.c)));
        if (isLast && reveal >= 1) {
          ctx!.shadowColor = color;
          ctx!.shadowBlur = 14;
        }
        ctx!.beginPath();
        ctx!.roundRect(x - bw / 2, top, bw, bh, 1.5);
        ctx!.fill();
        ctx!.shadowBlur = 0;
      }
      ctx!.globalAlpha = 1;

      // live price line + tag
      if (reveal >= 1) {
        const up = disp >= candles[n - 1].o;
        const yy = y(disp);
        ctx!.setLineDash([4, 4]);
        ctx!.strokeStyle = up ? col.up : col.down;
        ctx!.globalAlpha = 0.7;
        ctx!.beginPath();
        ctx!.moveTo(0, yy);
        ctx!.lineTo(plotW, yy);
        ctx!.stroke();
        ctx!.setLineDash([]);
        ctx!.globalAlpha = 1;
        ctx!.fillStyle = up ? col.up : col.down;
        ctx!.beginPath();
        ctx!.roundRect(plotW + 2, yy - 9, padR - 4, 18, 4);
        ctx!.fill();
        ctx!.fillStyle = '#fff';
        ctx!.font = '600 10px ui-monospace, SFMono-Regular, Menlo, monospace';
        ctx!.fillText(fmt(disp), plotW + 6, yy + 0.5);
      }
    }

    function tick() {
      const lc = candles[candles.length - 1];
      tickN++;
      if (tickN % 6 === 0) {
        candles.push({ o: lc.c, h: lc.c, l: lc.c, c: lc.c, v: 0.12 });
        candles.shift();
      } else {
        lc.c *= 1 + (Math.random() - 0.485) * 0.0016;
        lc.h = Math.max(lc.h, lc.c);
        lc.l = Math.min(lc.l, lc.c);
        lc.v = Math.min(1, lc.v + Math.random() * 0.1);
      }
      target = candles[candles.length - 1].c;
      setStat({ price: target, pct: (target / candles[0].o - 1) * 100 });
    }

    function frame(t: number) {
      const dt = Math.min(64, t - last);
      last = t;
      if (reveal < 1) {
        const p = Math.min(1, (t - startT) / 1500);
        reveal = 1 - Math.pow(1 - p, 3);
        if (p >= 1) reveal = 1;
      } else {
        acc += dt;
        if (acc >= 950) {
          acc = 0;
          tick();
        }
      }
      disp += (target - disp) * Math.min(1, dt * 0.008);
      draw();
      raf = requestAnimationFrame(frame);
    }

    function start() {
      if (running || reduced) return;
      running = true;
      last = performance.now();
      if (!startT) startT = last;
      raf = requestAnimationFrame(frame);
    }
    function stop() {
      running = false;
      cancelAnimationFrame(raf);
    }

    const ro = new ResizeObserver(resize);
    ro.observe(wrap);
    resize();

    const io = new IntersectionObserver(
      ([e]) => (e.isIntersecting ? start() : stop()),
      { threshold: 0.05 },
    );
    io.observe(wrap);

    return () => {
      stop();
      ro.disconnect();
      io.disconnect();
    };
  }, []);

  const up = stat.pct >= 0;

  return (
    <div className="lp-panel">
      <div className="flex items-start justify-between gap-3 p-4 pb-1">
        <div dir="ltr" className="text-start">
          <div className="flex items-center gap-2 text-sm font-semibold">
            <span className="lp-coin" style={{ '--h': 35 } as CSSProperties} aria-hidden>
              B
            </span>
            {pair}
          </div>
          <div
            className={cn(
              'tabular mt-1 text-3xl font-bold tracking-tight transition-colors duration-300 sm:text-4xl',
              up ? 'up' : 'down',
            )}
          >
            {fmt(stat.price)}
          </div>
        </div>
        <div className="flex flex-col items-end gap-2">
          <span className="lp-live">
            <i aria-hidden /> {liveLabel}
          </span>
          <span
            dir="ltr"
            className={cn('tabular rounded-md px-2 py-1 text-xs font-semibold', up ? 'bg-up/15 up' : 'bg-down/15 down')}
          >
            {up ? '+' : ''}
            {stat.pct.toFixed(2)}%
          </span>
        </div>
      </div>

      <div ref={wrapRef} dir="ltr" className="relative h-[260px] sm:h-[330px]">
        <canvas
          ref={canvasRef}
          className="absolute inset-0 size-full"
          role="img"
          aria-label={`${pair} candlestick chart`}
        />
      </div>

      <div className="grid grid-cols-2 gap-2 p-3 pt-2">
        <Link href={tradeHref} className="lp-trade lp-trade-buy">
          {buyLabel}
        </Link>
        <Link href={tradeHref} className="lp-trade lp-trade-sell">
          {sellLabel}
        </Link>
      </div>
      <p className="px-4 pb-3 text-center text-[11px] text-muted-foreground">{demoNote}</p>
    </div>
  );
}
