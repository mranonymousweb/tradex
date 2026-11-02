import { BotTypeEnum } from '../enum/bot-type.enum';

export interface IMarketPayload {
  id: number;
  pairs: string;
  type: BotTypeEnum;
}