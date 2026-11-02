import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { UserAssetService } from './user-asset.service';
import { CreateUserAssetDto } from './dto/create-user-asset.dto';
import { UpdateUserAssetDto } from './dto/update-user-asset.dto';

@Controller('user-asset')
export class UserAssetController {
  constructor(private readonly userAssetService: UserAssetService) {}

  @Post()
  create(@Body() createUserAssetDto: CreateUserAssetDto) {
    return this.userAssetService.create(createUserAssetDto);
  }

  @Get()
  findAll() {
    return this.userAssetService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.userAssetService.findOne(+id);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateUserAssetDto: UpdateUserAssetDto) {
    return this.userAssetService.update(+id, updateUserAssetDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.userAssetService.remove(+id);
  }
}
