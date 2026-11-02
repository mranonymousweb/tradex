import { Injectable } from '@nestjs/common';
import { RedisService } from 'src/redis/redis.service';
import { SpotMarketRegistryService } from './spot-market-registry.service';

export interface SpotTicker {
  changePercent: number;
  high24h: number;
  last: number;
  low24h: number;
  quoteVolume: number;
  symbol: string;
  volume: number;
  timestamp: number;
}

@Injectable()
export class SpotTickerConverterService {
  constructor(
    private readonly spotMarketRegistry: SpotMarketRegistryService,
    private readonly redisService: RedisService,
  ) {}

  async convertUsdtTickerToActiveBases(
    enrichedTicker: SpotTicker,
    crypto: string,
    onConverted: (ticker: SpotTicker) => Promise<void>,
  ): Promise<void> {
    const targetPairs = this.spotMarketRegistry.getSpotPairsForCrypto(crypto);
    if (!targetPairs.length) return;

    await Promise.all(
      targetPairs.map(async (symbol) => {
        const [, baseCurrencySymbol] = symbol.split('_');
        if (!baseCurrencySymbol || baseCurrencySymbol === 'USDT') return;

        const converter = await this.redisService.getPairPrice(`${baseCurrencySymbol}_USDT`);
        if (!converter?.last) return;

        const converterRate = Number(converter.last);
        if (!converterRate) return;

        const convertedTicker: SpotTicker = {
          changePercent: enrichedTicker.changePercent,
          high24h: Number(enrichedTicker.high24h) / converterRate,
          last: Number(enrichedTicker.last) / converterRate,
          low24h: Number(enrichedTicker.low24h) / converterRate,
          quoteVolume: Number(enrichedTicker.quoteVolume) / converterRate,
          symbol,
          volume: Number(enrichedTicker.volume),
          timestamp: enrichedTicker.timestamp,
        };

        await this.redisService.setPrice(convertedTicker.symbol, JSON.stringify(convertedTicker));
        await onConverted(convertedTicker);
      }),
    );
  }
}
