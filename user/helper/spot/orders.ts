import { useEffect, useState } from 'react';
import { getSocket } from './socket';

export const getOrderBook = (pair = 'BTC_USDT') => {
    const [data, setData] = useState<any>(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        if (!pair) return;

        setIsLoading(true);
        setData(null);

        const socket = getSocket();

        const timeout = setTimeout(() => {
            setIsLoading(false);
        }, 5000);


        const handler = (payload: any) => {
            clearTimeout(timeout);
            setData(payload);
            setIsLoading(false);
        };

        socket.on('all:orders', handler);
        socket.emit('subscribe:all:orders', { pair });

        return () => {
            clearTimeout(timeout);
            socket.off('all:orders', handler);
            socket.emit('unsubscribe:all:orders', { pair });
        };
    }, [pair]);

    return { orders: data, isOrdersLoading: isLoading };
};
