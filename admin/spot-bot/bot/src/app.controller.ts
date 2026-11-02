import { Controller, Get, Res } from '@nestjs/common';
import { AppService } from './app.service';
import { join } from 'path';
import type { Response } from 'express';

@Controller()
export class AppController {
  constructor(private readonly appService: AppService) {}

  @Get()
  async getHello(): Promise<string> {
    return await this.appService.getHello();
  }

  @Get('bot-view')
  getBotView(@Res() res: Response) {
    return res.sendFile(
      join(process.cwd(), 'src', 'public', 'bot-view.html')
    );
  }
}
