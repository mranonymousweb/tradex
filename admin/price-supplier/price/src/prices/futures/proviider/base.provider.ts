// exchange.base.ts
import { Logger } from '@nestjs/common';

export abstract class ProviderBase {
  protected isConnected = false;
  protected isConnecting = false;

  // protected logger = new Logger(ExchangeBase.name);


  /** نام صرافی */
  abstract readonly name: string;

  /** اتصال websocket یا API به صرافی */
  abstract connect(): Promise<void>;

  /** قطع اتصال */
  abstract disconnect(): Promise<void>;

  /** پردازش دیتا (override در هر صرافی) */
  protected abstract onMessage(data: any): Promise<void>;

  protected splitSymbol(symbol: string) {
    // لیست quoteهای رایج در Binance Futures
    const QUOTES = ['USDT', 'BUSD', 'BTC', 'ETH', 'BNB'];
    
    for (const quote of QUOTES) {
      if (symbol.endsWith(quote)) {
        const base = symbol.slice(0, symbol.length - quote.length);
        return { base, quote };
      }
    }

    // اگر quote شناخته نشد، همه را base در نظر بگیر
    return { base: symbol, quote: '' };
  }

}