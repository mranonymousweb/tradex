import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { ValidationPipe } from '@nestjs/common';
import { Transport } from '@nestjs/microservices';
import { ConfigService, ConfigType } from '@nestjs/config';
import { CompressionCodecs, CompressionTypes } from 'kafkajs';
import SnappyCodec from 'kafkajs-snappy';

async function bootstrap() {
  CompressionCodecs[CompressionTypes.Snappy] = SnappyCodec;
  const app = await NestFactory.create(AppModule);

  app.useGlobalPipes(
    new ValidationPipe({
      whitelist: true,
      transform: true,
    }),
  );
  // console.log("prot is: ", process.env.STOCKPILE_PORT ?? 3060)
  
  await app.listen(3060);
}
bootstrap();
