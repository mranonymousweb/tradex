import { useEffect, useState } from 'react';
import { getSocket } from './socket';

export const tableOrders = (pair: string | null) => {
  const [data, setData] = useState<any>(null);

  useEffect(() => {
    if (!pair) return;
    const socket = getSocket();
    const handler = (payload: any) => {
      setData(payload);
    };

    socket.on('my:orders', handler);
    socket.emit('subscribe:my:orders', { pair });

    return () => {
      socket.off('my:orders', handler);
      socket.emit('unsubscribe:my:orders', { pair });
    };
  }, [pair]);

  return data?.data?.order;
};
