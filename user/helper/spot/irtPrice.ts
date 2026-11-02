import { useEffect, useState } from 'react';
import { getSocket } from './socket';

const socket = getSocket();
const EMIT = 'fiat:USDT_IRT';
const MESSAGE = { pair: 'USDT_IRT' };

const useFiatPrice = () => {
  const [last, setLast] = useState<number>(0);

  useEffect(() => {
    const handler = (payload: any) => {
      setLast(payload?.data?.last ?? 0);
    };

    const subscribe = () => {
      socket.emit(`subscribe:${EMIT}`, MESSAGE);
      socket.on(EMIT, handler);
    };

    if (socket.connected) {
      subscribe();
    } else {
      socket.once('connect', subscribe);
    }

    return () => {
      socket.off(EMIT, handler);
      socket.off('connect', subscribe);
      socket.emit(`unsubscribe:${EMIT}`, MESSAGE);
    };
  }, []);

  return last;
};

export const usdtIrtPrice = (enable: boolean) => {
  const price = useFiatPrice();
  return enable ? price : undefined;
};

export const usdPrice = () => useFiatPrice();
