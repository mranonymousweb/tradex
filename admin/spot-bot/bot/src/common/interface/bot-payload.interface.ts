import { BotTypeEnum } from "../enum/bot-type.enum";

export interface IBotPayload {
    _id: string;
    leastAmount: number;
    mostAmount: number;
    sellPercent: number;
    buyPercent: number;
    donePercent: number;
    status: number;
    secount: number;
    type?: BotTypeEnum;

    minSpread?: number;
    maxSpread?: number;
    minOrders?: number;
    maxOrders?: number;
    currencies?: Array<string>;
    symbol?: string;

}