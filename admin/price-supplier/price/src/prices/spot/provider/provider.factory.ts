import { Injectable } from '@nestjs/common';
import { GateioProvider } from './gateio.provider';
import { SPOT_PROVIDER_NAME } from './provider.interface';
import { NobitexProvider } from './nobitex.provider';
import { CoinexProvider } from './coinex.provider';

// @Injectable()
// export class ProviderFactory {
//   constructor(private readonly gateio: GateioProvider) {}

//   get(exchangeName?: string) {
//     if (exchangeName && exchangeName !== SPOT_PROVIDER_NAME) {
//       console.warn(
//         `[ProviderFactory] Unsupported provider "${exchangeName}", falling back to ${SPOT_PROVIDER_NAME}`,
//       );
//     }

//     return this.gateio;
//   }

//   getAll() {
//     return [this.gateio];
//   }
// }
@Injectable()
export class ProviderFactory {
  constructor(
    private readonly gate_io: GateioProvider,
    private readonly nobitex: NobitexProvider,
    private readonly coinex: CoinexProvider
  ) {}

  get(exchangeName: string) {
    switch (exchangeName) {
      case 'gate_io':
        return this.gate_io;
      case 'nobitex':
        return this.nobitex;
      case 'coinex':
        return this.coinex;
      default:
        throw new Error(`Exchange ${exchangeName} not supported`);
    }
  }

  getAll() {
    return [this.gate_io];
  }
}
