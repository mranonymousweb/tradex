export type LandingLang = 'fa' | 'en';

export function pickLang(locale: string): LandingLang {
  return locale === 'fa' ? 'fa' : 'en';
}

const fa = {
  nav: {
    markets: 'بازارها',
    features: 'امکانات',
    steps: 'شروع کار',
    security: 'امنیت',
    signin: 'ورود',
    signup: 'ثبت‌نام',
    menu: 'منو',
  },
  hero: {
    live: 'بازار زنده',
    title1: 'ارز دیجیتال را با تومان',
    title2: 'سریع معامله کن',
    sub: 'اسپات، فیوچرز و خرید فوری در یک حساب. قیمت‌ها لحظه‌ای، واریز و برداشت ساده، و پشتیبانی از طریق تیکت.',
    primary: 'ساخت حساب رایگان',
    secondary: 'مشاهده بازارها',
    buy: 'خرید',
    sell: 'فروش',
    demo: 'نمودار نمونه است و برای نمایش شبیه‌سازی شده.',
    chips: ['ورود دو مرحله‌ای', 'احراز هویت', 'پشتیبانی تیکتی'],
  },
  markets: {
    title: 'قیمت لحظه‌ای ارزها',
    sub: 'برترین ارزها را ببین و مستقیم وارد بازارشان شو.',
    coin: 'ارز',
    price: 'قیمت',
    change: 'تغییر ۲۴ ساعته',
    chart: 'روند',
    trade: 'معامله',
    all: 'مشاهده همه ارزها',
    sample: 'قیمت‌های این جدول نمونه‌اند؛ قیمت واقعی را در صفحه بازارها ببین.',
  },
  features: {
    title: 'هر بازاری که لازم داری',
    sub: 'از اولین خرید تا معامله‌ی حرفه‌ای، همه‌چیز در یک پنل.',
    spot: {
      t: 'بازار اسپات',
      d: 'سفارش بازار و محدود روی دفتر سفارش زنده، با نمودار حرفه‌ای.',
      cta: 'ورود به اسپات',
      ask: 'فروش',
      bid: 'خرید',
    },
    futures: {
      t: 'بازار فیوچرز',
      d: 'با اهرم معامله کن و ریسک هر موقعیت را خودت تنظیم کن.',
      cta: 'ورود به فیوچرز',
      lev: 'اهرم',
    },
    swap: {
      t: 'خرید فوری',
      d: 'تبدیل ارز به ارز در چند ثانیه، بدون رفتن سراغ دفتر سفارش.',
      cta: 'تبدیل فوری',
    },
    stock: {
      t: 'سود سپرده',
      d: 'ارزهایی که به آن‌ها نیاز نداری را سپرده‌گذاری کن تا برایت سود بسازند.',
      cta: 'شروع سپرده‌گذاری',
    },
  },
  steps: {
    title: 'از ثبت‌نام تا اولین معامله',
    sub: 'چهار قدم کوتاه؛ بیشتر آن‌ها را در چند دقیقه تمام می‌کنی.',
    items: [
      { t: 'ثبت‌نام', d: 'با ایمیل یا شماره موبایل حساب بساز.' },
      { t: 'احراز هویت', d: 'مدارکت را ثبت کن تا سقف معاملاتت باز شود.' },
      { t: 'واریز', d: 'کارت بانکی را ثبت کن و کیف پولت را شارژ کن.' },
      { t: 'معامله', d: 'ارز مورد نظرت را بخر، بفروش یا تبدیل کن.' },
    ],
  },
  trust: {
    title: 'دارایی‌ات امن، پشتیبانی‌ات نزدیک',
    sub: 'امنیت حساب فقط یک قابلیت نیست؛ از ورود تا برداشت در هر مرحله حضور دارد.',
    items: [
      { t: 'ورود دو مرحله‌ای', d: 'با شناسایی دو عاملی جلوی دسترسی غیرمجاز را بگیر.' },
      { t: 'سطح‌بندی کاربری', d: 'با تکمیل احراز هویت، سطح و سقف معاملاتت بالا می‌رود.' },
      { t: 'اعلان‌های لحظه‌ای', d: 'از ورود، واریز و برداشت‌ها همان لحظه خبردار شو.' },
      { t: 'پشتیبانی تیکتی', d: 'هر سوالی داشتی تیکت ثبت کن و پاسخ را در پنل ببین.' },
    ],
  },
  cta: {
    title: 'اولین معامله‌ات را همین امروز انجام بده',
    sub: 'ثبت‌نام رایگان است. دوستانت را هم دعوت کن و از دعوتشان سود ببر.',
    primary: 'ساخت حساب رایگان',
    secondary: 'ورود به حساب',
  },
  footer: {
    product: 'محصول',
    account: 'حساب',
    help: 'راهنما',
    markets: 'قیمت ارزها',
    spot: 'بازار اسپات',
    futures: 'بازار فیوچرز',
    swap: 'خرید فوری',
    signin: 'ورود',
    signup: 'ثبت‌نام',
    ticket: 'ثبت تیکت',
    rights: 'تمامی حقوق محفوظ است.',
  },
};

const en: typeof fa = {
  nav: {
    markets: 'Markets',
    features: 'Features',
    steps: 'Get started',
    security: 'Security',
    signin: 'Sign in',
    signup: 'Sign up',
    menu: 'Menu',
  },
  hero: {
    live: 'Live market',
    title1: 'Trade crypto',
    title2: 'fast and with ease',
    sub: 'Spot, futures and instant buy in one account. Live prices, simple deposits and withdrawals, and ticket-based support.',
    primary: 'Create free account',
    secondary: 'View markets',
    buy: 'Buy',
    sell: 'Sell',
    demo: 'Sample chart, simulated for display.',
    chips: ['Two-factor login', 'Identity verification', 'Ticket support'],
  },
  markets: {
    title: 'Live prices',
    sub: 'Browse top coins and jump straight into their market.',
    coin: 'Coin',
    price: 'Price',
    change: '24h change',
    chart: 'Trend',
    trade: 'Trade',
    all: 'View all coins',
    sample: 'Prices in this table are samples; see the Markets page for real prices.',
  },
  features: {
    title: 'Every market you need',
    sub: 'From your first purchase to pro trading, all in one panel.',
    spot: {
      t: 'Spot market',
      d: 'Market and limit orders on a live order book, with a pro chart.',
      cta: 'Open spot',
      ask: 'Sell',
      bid: 'Buy',
    },
    futures: {
      t: 'Futures market',
      d: 'Trade with leverage and set the risk of every position yourself.',
      cta: 'Open futures',
      lev: 'Leverage',
    },
    swap: {
      t: 'Instant buy',
      d: 'Convert coin to coin in seconds, no order book needed.',
      cta: 'Convert now',
    },
    stock: {
      t: 'Deposit interest',
      d: 'Deposit coins you are not using and let them earn for you.',
      cta: 'Start earning',
    },
  },
  steps: {
    title: 'From sign-up to first trade',
    sub: 'Four short steps; you can finish most of them in minutes.',
    items: [
      { t: 'Sign up', d: 'Create an account with email or phone.' },
      { t: 'Verify identity', d: 'Submit your documents to unlock your trading limit.' },
      { t: 'Deposit', d: 'Add a bank card and fund your wallet.' },
      { t: 'Trade', d: 'Buy, sell or convert the coin you want.' },
    ],
  },
  trust: {
    title: 'Your assets safe, support close',
    sub: 'Account security is not one feature; it is present from login to withdrawal.',
    items: [
      { t: 'Two-factor login', d: 'Block unauthorized access with two-factor authentication.' },
      { t: 'User levels', d: 'Complete verification to raise your level and limits.' },
      { t: 'Instant alerts', d: 'Know about logins, deposits and withdrawals right away.' },
      { t: 'Ticket support', d: 'Open a ticket and read the answer in your panel.' },
    ],
  },
  cta: {
    title: 'Make your first trade today',
    sub: 'Sign-up is free. Invite friends and earn from their activity.',
    primary: 'Create free account',
    secondary: 'Sign in',
  },
  footer: {
    product: 'Product',
    account: 'Account',
    help: 'Help',
    markets: 'Markets',
    spot: 'Spot',
    futures: 'Futures',
    swap: 'Instant buy',
    signin: 'Sign in',
    signup: 'Sign up',
    ticket: 'Open a ticket',
    rights: 'All rights reserved.',
  },
};

export const copy = { fa, en };
export type LandingCopy = typeof fa;
