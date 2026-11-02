

پنل کاربری صرافی ارز دیجیتال (Aryagostaran) — فرانت‌اند مبتنی بر Next.js برای مدیریت حساب کاربری، معاملات Spot و Futures، کیف پول، بازارها و سایر ماژول‌های پلتفرم.

---

## فهرست مطالب

- [ساختار پروژه](#ساختار-پروژه)
- [تکنولوژی‌های استفاده شده](#تکنولوژیهای-استفاده-شده)
- [نصب و راه‌اندازی](#نصب-و-راهاندازی)
- [WebSocket — اتصال، Eventها و Connection](#websocket--اتصال-eventها-و-connection)
- [اسکریپت‌های NPM](#اسکریپتهای-npm)

---

## ساختار پروژه

```
ex-arya-front/
├── app/                        # مسیرها و صفحات (App Router)
│   ├── (auth)/                 # صفحات احراز هویت (ورود، ثبت‌نام، تغییر رمز و ...)
│   ├── (pages)/                # صفحات اصلی پنل کاربری
│   │   ├── trade/spot/         # معاملات اسپات
│   │   ├── trade/futures/      # معاملات فیوچرز
│   │   ├── wallet/             # کیف پول (واریز، برداشت، گزارش)
│   │   ├── markets/            # لیست بازارها
│   │   ├── swap/               # تبدیل ارز
│   │   ├── assets/             # دارایی‌ها
│   │   ├── marketing/          # بازاریابی
│   │   ├── ticket/             # تیکت پشتیبانی
│   │   └── ...
│   ├── (receipt)/              # صفحات رسید و بازگشت از درگاه
│   ├── api/                    # Route Handlerهای API داخلی Next.js
│   └── models/                 # مدل‌ها و فونت‌ها
│
├── components/                 # کامپوننت‌های UI
│   ├── ui/                     # کامپوننت‌های پایه (shadcn/Radix)
│   ├── layouts/                # لایه‌بندی صفحات
│   ├── partials/               # بخش‌های مشترک (منو، هدر، کارت و ...)
│   └── common/                 # کامپوننت‌های عمومی
│
├── services/                   # فراخوانی API بک‌اند (Server Actions)
├── helper/                     # منطق سمت کلاینت و هوک‌های WebSocket
│   ├── spot/                   # Spot: قیمت، سفارش، socket
│   ├── futures/                # Futures: قیمت، سفارش، socket
│   ├── markets/                # قیمت‌گذاری بازارها
│   ├── swap/                   # تبدیل ارز
│   ├── wallet/                 # کیف پول
│   └── ...
│
├── lib/                        # ابزارهای هسته (apiFetch، session، auth و ...)
├── providers/                  # Context Providerها (Query، Theme، Auth و ...)
├── hooks/                      # React Hookهای عمومی
├── types/                      # تایپ‌های TypeScript
├── utils/                      # توابع کمکی
├── config/                     # تنظیمات منو و UI
├── i18n/                       # فایل‌های ترجمه (fa, en, ar, ...)
├── css/                        # استایل‌های سراسری
└── public/                     # فایل‌های استاتیک (چارت TradingView و ...)
```

### توضیح لایه‌ها

| پوشه | نقش |
|------|-----|
| `app/` | تعریف Routeها با Next.js App Router. گروه‌های `(auth)`، `(pages)` و `(receipt)` layout جداگانه دارند. |
| `services/` | Server Actionها برای ارتباط با REST API بک‌اند از طریق `apiFetch`. |
| `helper/` | منطق کلاینت؛ به‌ویژه اشتراک‌گذاری WebSocket برای قیمت لحظه‌ای و سفارشات. |
| `lib/api.ts` | لایه مرکزی HTTP با پشتیبانی از توکن، locale، retry و timeout. |
| `providers/` | Providerهای React برای React Query، تم، احراز هویت و تنظیمات UI. |

### صفحات اصلی

| مسیر | توضیح |
|------|-------|
| `/` | داشبورد |
| `/trade/spot` | معاملات اسپات |
| `/trade/futures` | معاملات فیوچرز |
| `/markets` | بازارها |
| `/wallet/*` | واریز، برداشت، گزارش کیف پول |
| `/swap` | تبدیل ارز |
| `/signin`, `/signup` | ورود و ثبت‌نام |

---

## تکنولوژی‌های استفاده شده

### هسته

| تکنولوژی | نسخه | کاربرد |
|----------|------|--------|
| **Next.js** | 16.x | فریم‌ورک React با App Router و Server Actions |
| **React** | 19.x | رابط کاربری |
| **TypeScript** | 5.x | تایپ‌گذاری استاتیک |
| **Tailwind CSS** | 4.x | استایل‌دهی |

### داده و وضعیت

| تکنولوژی | کاربرد |
|----------|--------|
| **TanStack React Query** | مدیریت cache و fetch داده‌های API |
| **Socket.IO Client** | اتصال WebSocket برای داده‌های لحظه‌ای |
| **React Hook Form + Zod** | فرم‌ها و اعتبارسنجی |

### UI و تجربه کاربری

| تکنولوژی | کاربرد |
|----------|--------|
| **Radix UI** | کامپوننت‌های دسترسی‌پذیر |
| **Lucide / Remix Icon** | آیکون‌ها |
| **next-intl** | چندزبانه (فارسی، انگلیسی، عربی و ...) |
| **next-themes** | تم روشن/تاریک |
| **ApexCharts / Recharts** | نمودارها |
| **TradingView Charting Library** | چارت معاملاتی (`public/chart/`) |

### احراز هویت و امنیت

| تکنولوژی | کاربرد |
|----------|--------|
| **NextAuth** | مدیریت session |
| **react-google-recaptcha-v3** | کپچا |
| **jose** | JWT |

### سایر

- **date-fns** — تاریخ و زمان
- **sonner** — نوتیفیکیشن toast

---

## نصب و راه‌اندازی

### پیش‌نیازها

- **Node.js** 18 یا بالاتر
- **npm** (یا yarn/pnpm)
- دسترسی به **API بک‌اند** و **سرور WebSocket**

### مراحل نصب

**1. نصب وابستگی‌ها**

```bash
npm install
```

**2. تنظیم متغیرهای محیطی**

فایل `.env.example` را کپی کنید:

```bash
cp .env.example .env
```

متغیرهای مهم:

| متغیر | توضیح | مثال |
|-------|-------|------|
| `NEXT_PUBLIC_API_BACKEND_URL` | آدرس API بک‌اند | `https://api.example.net` |
| `NEXT_PUBLIC_API_FRONTEND_URL` | آدرس پنل کاربری | `http://localhost:3000` |
| `NEXT_PUBLIC_DOMAIN_PANEL` | دامنه پنل | `localhost` |
| `NEXT_PUBLIC_SOCKET_PUBLIC` | آدرس WebSocket | `wss://wss.example.net` |
| `NEXT_PUBLIC_DEFAULT_LANGUAGE` | زبان پیش‌فرض | `fa` |
| `NEXT_PUBLIC_RECAPTCHA_SITE_KEY` | کلید سایت reCAPTCHA | — |
| `RECAPTCHA_SECRET_KEY` | کلید مخفی reCAPTCHA | — |
| `SECRET_KEY` | کلید رمزنگاری session | تولید با دستور زیر |
| `MODE_ENV` | محیط اجرا | `development` / `production` |
| `NEXT_PUBLIC_ENABLE_SPOT` | فعال‌سازی اسپات | `true` / `false` |
| `NEXT_PUBLIC_ENABLE_FUTURES` | فعال‌سازی فیوچرز | `true` / `false` |
| `HOURS_PER_DAY` | مدت session (ساعت) | `168` (۷ روز) |

تولید `SECRET_KEY`:

```bash
node -e "console.log(require('crypto').randomBytes(32).toString('base64'))"
```

**3. اجرای محیط توسعه**

```bash
npm run dev
```

پروژه روی [http://localhost:3000](http://localhost:3000) در دسترس است.

**4. Build و اجرای Production**

```bash
npm run build
npm start
```

در production، اپلیکیشن روی **پورت 3005** اجرا می‌شود.

### ساختار API

تمام درخواست‌های HTTP از `lib/api.ts` عبور می‌کنند:

```
{NEXT_PUBLIC_API_BACKEND_URL}/api/v1/user{endpoint}
```

هدرهای ارسالی:
- `Authorization: Bearer {token}` — در صورت وجود session
- `Accept-Language` — locale فعلی
- `Accept-Domain` — آدرس فرانت‌اند

---

## WebSocket — اتصال، Eventها و Connection

پروژه از **Socket.IO Client** برای دریافت داده‌های لحظه‌ای (قیمت، سفارش‌های کاربر) استفاده می‌کند.

### معماری اتصال

دو namespace جداگانه وجود دارد:

| Namespace | فایل | آدرس |
|-----------|------|------|
| **Spot** | `helper/spot/socket.ts` | `{NEXT_PUBLIC_SOCKET_PUBLIC}/spot` |
| **Futures** | `helper/futures/socket.ts` | `{NEXT_PUBLIC_SOCKET_PUBLIC}/future` |

هر namespace یک **Singleton Socket** دارد؛ با اولین فراخوانی `getSocket()` اتصال برقرار می‌شود.

```typescript
// helper/spot/socket.ts
socket = io(`${process.env.NEXT_PUBLIC_SOCKET_PUBLIC}/spot`, {
  transports: ['websocket'],
  reconnectionDelay: 3000,
  reconnectionDelayMax: 10000,
  reconnectionAttempts: 5,
  autoConnect: false,
});
socket.connect();
```

### تنظیمات Connection

| پارامتر | مقدار | توضیح |
|---------|-------|-------|
| `transports` | `['websocket']` | فقط WebSocket (بدون polling) |
| `reconnectionDelay` | 3000ms | تأخیر اولین تلاش reconnect |
| `reconnectionDelayMax` | 10000ms | حداکثر تأخیر reconnect |
| `reconnectionAttempts` | 5 | حداکثر تعداد تلاش reconnect |
| `autoConnect` | `false` | اتصال دستی با `socket.connect()` |

### الگوی Subscribe / Unsubscribe

الگوی مشترک در تمام helperها:

1. **Subscribe:** `socket.emit('subscribe:{event}', { pair })`
2. **Listen:** `socket.on('{event}', handler)`
3. **Cleanup (در useEffect return):**
   - `socket.emit('unsubscribe:{event}', { pair })`
   - `socket.off('{event}', handler)`

### Eventهای Spot (`/spot`)

| Event | Subscribe | Payload `pair` | توضیح | فایل استفاده‌کننده |
|-------|-----------|----------------|-------|-------------------|
| `pair:price` | `subscribe:pair:price` | آرایه جفت‌ها مثل `['BTC_USDT', 'ETH_USDT']` | قیمت لحظه‌ای جفت‌ها | `helper/spot/currencyPrice.ts`, `helper/markets/markets.ts`, `helper/swap/list-currency.ts` |
| `fiat:USDT_IRT` | `subscribe:fiat:USDT_IRT` | `'USDT_IRT'` | نرخ تتر به ریال | `helper/spot/irtPrice.ts`, `helper/swap/list-currency.ts` |
| `all:orders` | `subscribe:all:orders` | یک جفت مثل `'BTC_USDT'` | Order Book | `helper/spot/orders.ts` |
| `my:orders` | `subscribe:my:orders` | یک جفت مثل `'BTC_USDT'` | سفارش‌های باز کاربر | `helper/spot/tableOrders.ts` |

**نمونه payload دریافتی (`pair:price`):**

```json
{
  "data": {
    "symbol": "BTC_USDT",
    "last": 65000.5,
    "changePercent": 1.25
  }
}
```

### Eventهای Futures (`/future`)

| Event | Subscribe | Payload `pair` | توضیح | فایل استفاده‌کننده |
|-------|-----------|----------------|-------|-------------------|
| `future:pair:price` | `subscribe:future:pair:price` | آرایه جفت‌ها | قیمت لحظه‌ای فیوچرز | `helper/futures/currencyPrice.ts` |
| `future:all:orders` | `subscribe:future:all:orders` | یک جفت | Order Book فیوچرز | `helper/futures/currencyPrice.ts` |
| `future:my:orders` | `subscribe:future:my:orders` | `{userId}-{pair}` مثل `123-BTC_USDT` | سفارش‌های باز کاربر | `helper/futures/currencyPrice.ts` |

**نمونه payload دریافتی (`future:pair:price`):**

```json
{
  "data": {
    "symbol": "BTC_USDT",
    "price": 65000.5,
    "changePercent": 1.25,
    "indexPrice": 64980.0
  }
}
```

### نمونه استفاده در کامپوننت

```typescript
import { useEffect, useState } from 'react';
import { getSocket } from '@/helper/spot/socket';

function OrderBook({ pair }: { pair: string }) {
  const [orders, setOrders] = useState(null);
  const socket = getSocket();

  useEffect(() => {
    const handler = (payload: any) => setOrders(payload);

    socket.on('all:orders', handler);
    socket.emit('subscribe:all:orders', { pair });

    return () => {
      socket.off('all:orders', handler);
      socket.emit('unsubscribe:all:orders', { pair });
    };
  }, [pair]);

  return /* ... */;
}
```

### نکات مهم

1. **همیشه cleanup کنید** — در `useEffect` حتماً `unsubscribe` و `socket.off` را فراخوانی کنید تا memory leak و subscription تکراری نداشته باشید.
2. **تغییر جفت (pair)** — با تغییر `pair` در dependency array، effect قبلی cleanup و subscription جدید ایجاد می‌شود.
3. **اتصال اولیه** — در `irtPrice.ts` اگر socket هنوز connect نشده باشد، با `socket.once('connect', subscribe)` منتظر اتصال می‌ماند.
4. **Buffer قیمت Spot** — در `currencyPrice.ts` داده‌ها هر ۱۰ ثانیه از buffer به state flush می‌شوند (`FLUSH_INTERVAL = 10_000`) تا re-render زیاد نشود.
5. **Timeout Order Book** — اگر تا ۵ ثانیه داده‌ای دریافت نشود، loading متوقف می‌شود.
6. **متغیر محیطی** — `NEXT_PUBLIC_SOCKET_PUBLIC` باید با پروتکل `wss://` (production) یا `ws://` (development) تنظیم شود.

### فلوچارت اتصال

```
┌─────────────┐     getSocket()      ┌──────────────────┐
│  Component  │ ──────────────────►  │  Socket Instance │
│  (React)    │                      │  (Singleton)     │
└─────────────┘                      └────────┬─────────┘
       │                                      │
       │  emit('subscribe:event', {pair})     │  connect()
       ├─────────────────────────────────────►│
       │                                      ▼
       │                              ┌───────────────┐
       │  on('event', handler)        │  WebSocket    │
       │◄─────────────────────────────│  Server       │
       │                              └───────────────┘
       │  cleanup: off + unsubscribe
       └─────────────────────────────────────►
```

---

## اسکریپت‌های NPM

| دستور | توضیح |
|-------|-------|
| `npm run dev` | اجرای سرور توسعه (پورت 3000) |
| `npm run build` | Build production |
| `npm start` | اجرای production (پورت 3005) |
| `npm run lint` | بررسی ESLint |
| `npm run format` | فرمت کد با Prettier |

---
