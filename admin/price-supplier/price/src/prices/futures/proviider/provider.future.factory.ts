import { Injectable } from "@nestjs/common";
import { BinanceFuturesMarketProvider } from "./binance.future.market.provider";
import { BinanceFuturesTickerProvider } from "./binance.future.ticker.provider";
import { BybitFuturesProvider } from "./bybit.future.provider";
import { GateFuturesMarketProvider } from "./gateio.future.market.provider";
import { GateFuturesTickerProvider } from "./gateio.future.ticker.provider";




@Injectable()
export class ProviderFutureFactory {
  constructor(
    private readonly binanceMarket: BinanceFuturesMarketProvider,
    private readonly binanceTicker: BinanceFuturesTickerProvider,
    private readonly bybit: BybitFuturesProvider,
    private readonly gateioMarket: GateFuturesMarketProvider,
    private readonly gateioTicker: GateFuturesTickerProvider
  ) {}

  get(exchangeName: string) {
    switch (exchangeName) {
      case 'binance':
        return [this.binanceMarket, this.binanceTicker];
      case 'bybit':
        return [this.bybit];
      case 'gate_io':
        return [this.gateioMarket, this.gateioTicker];
      default:
        console.warn(`Exchange ${exchangeName} not supported`);
    }
  }

  getAll() {
    return [this.binanceMarket, this.binanceTicker];
  }
}
