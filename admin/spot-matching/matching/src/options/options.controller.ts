import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { OptionsService } from './options.service';
import { CreateOptionDto } from './dto/create-option.dto';
import { UpdateOptionDto } from './dto/update-option.dto';

@Controller('opions')
export class OptionsController {
  constructor(private readonly opionsService: OptionsService) {}

  @Post()
  create(@Body() createOptionDto: CreateOptionDto) {
    return this.opionsService.create(createOptionDto);
  }

  @Get()
  findAll() {
    return this.opionsService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.opionsService.findOne(+id);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateOptionDto: UpdateOptionDto) {
    return this.opionsService.update(+id, updateOptionDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.opionsService.remove(+id);
  }
}
