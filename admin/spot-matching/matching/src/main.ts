import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { ValidationPipe } from '@nestjs/common';
import { CompressionTypes, CompressionCodecs } from 'kafkajs';
const SnappyCodec = require('kafkajs-snappy');

CompressionCodecs[CompressionTypes.Snappy] = SnappyCodec;

async function bootstrap() {
  const app = await NestFactory.create(AppModule);

  app.useGlobalPipes(
    new ValidationPipe({
      whitelist: true,
      transform: true,
    }),
  );
  
  await app.listen(process.env.ENGINE_PORT ?? 3000);
}
bootstrap();
