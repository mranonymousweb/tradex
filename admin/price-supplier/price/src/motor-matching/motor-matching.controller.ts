import { Controller } from '@nestjs/common';
import { EventPattern, Payload } from '@nestjs/microservices';
import { RedisService } from 'src/redis/redis.service';

@Controller('motor-matching')
export class MotorMatchingController {
    constructor(
        private readonly redisService: RedisService,
    ) {}

    // @EventPattern('prices-maching')
    // async handle(@Payload() message: any) {

    //     // 👇 publish روی چنل جدید
    //     await this.redisService.publish(
    //         'prices:maching',
    //         JSON.stringify(message),
    //     );

    // }
}
