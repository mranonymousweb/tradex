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


   protected getRelayUrl() {
    return process.env.ARIA_URL
    const region = process.env.REGION;

    if (region === 'ir') return 'ws://127.0.0.1:3061';
    if (region === 'de') return 'ws://127.0.0.1:3061';

    throw new Error('unknown region');
  }


}