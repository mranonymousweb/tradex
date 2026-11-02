import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { ValidationPipe } from '@nestjs/common';
import { CompressionTypes, CompressionCodecs } from 'kafkajs';
import { join } from 'path';
import { NestExpressApplication } from '@nestjs/platform-express';
const SnappyCodec = require('kafkajs-snappy');

CompressionCodecs[CompressionTypes.Snappy] = SnappyCodec;

async function bootstrap() {
  const app = await NestFactory.create<NestExpressApplication>(AppModule);

  app.useGlobalPipes(
    new ValidationPipe({
      whitelist: true,
      transform: true,
    }),
  );

  app.useStaticAssets(join(__dirname, '..', 'public'));
  
  await app.listen(process.env.BOT_PORT ?? 4038);
}
bootstrap();
