import {Injectable, Logger } from '@nestjs/common';
import { EventGateway } from './event.gateway';
import { IOrder } from 'src/common/interface/order.interface';

@Injectable()
export class EventService {
  constructor(private eventGateway: EventGateway) {}

  broadcastBotOrders(marketPair: string, buyOrders: IOrder[], sellOrders: IOrder[], doneOrders: IOrder[]) {
    try {
        this.eventGateway.server.emit(`bot.${marketPair}`, {
          timestamp: new Date().toISOString(),
          marketPair,
          data: {
            sellers: sellOrders,
            buyers: buyOrders,
            done: doneOrders
          },
        });
    } catch (err) {
      Logger.error(err);
    }
  }
}