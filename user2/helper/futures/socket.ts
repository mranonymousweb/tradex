import { io, Socket } from 'socket.io-client';

let socket: Socket | null = null;

export function getSocket(): Socket {
  if (!socket) {
    socket = io(`${process.env.NEXT_PUBLIC_SOCKET_PUBLIC}/future`, {
      transports: ['websocket'],
      reconnectionDelay: 3000,
      reconnectionDelayMax: 10000,
      reconnectionAttempts: 5,
      autoConnect: false,
    });
    socket.connect();
  }
  return socket;
}
