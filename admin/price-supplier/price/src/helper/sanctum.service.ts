import { Injectable } from '@nestjs/common';
import { DataSource } from 'typeorm';
import * as crypto from 'crypto';

@Injectable()
export class SanctumService {
  constructor(private readonly dataSource: DataSource) {}

  async validateToken(token: string) {
    if (!token || !token.includes('|')) return null;

    const [id, plainToken] = token.split('|');

    const hashedToken = crypto
      .createHash('sha256')
      .update(plainToken)
      .digest('hex');

    const result = await this.dataSource.query(
      `SELECT * FROM personal_access_tokens WHERE id = ? AND token = ?`,
      [id, hashedToken],
    );

    return result[0] ?? null;
  }
}
