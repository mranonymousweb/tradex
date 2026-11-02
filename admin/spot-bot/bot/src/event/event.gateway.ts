import {
  WebSocketGateway,
  WebSocketServer,
  OnGatewayConnection,
  OnGatewayDisconnect,
  SubscribeMessage,
  MessageBody,
  ConnectedSocket,
} from '@nestjs/websockets';
import { forwardRef, Inject, Logger } from '@nestjs/common';
import { Server, Socket } from 'socket.io';
import { AppService } from 'src/app.service';

@WebSocketGateway({
  cors: {
    origin: '*',
  },
  namespace: 'bot',
})
export class EventGateway implements OnGatewayConnection, OnGatewayDisconnect {
  @WebSocketServer()
  server: Server;

  constructor(@Inject(forwardRef(() => AppService)) private appService: AppService) {}

  handleConnection(client: Socket) {
    Logger.verbose('connect', client.id);
  }

  handleDisconnect(client: Socket) {
    Logger.verbose('disconnect', client.id);
  }


}