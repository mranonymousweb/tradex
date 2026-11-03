/* eslint-disable @typescript-eslint/no-explicit-any */

import { JWTPayload, jwtVerify, SignJWT } from 'jose';

const key = new TextEncoder().encode(process.env.SECRET_KEY);
const HOURS_PER_DAY = Number(process.env.HOURS_PER_DAY) || 168;

export async function encrypt(payload: JWTPayload | undefined) {
  return await new SignJWT(payload)
    .setProtectedHeader({ alg: 'HS256' })
    .setIssuedAt()
    .setExpirationTime(`${HOURS_PER_DAY}h`)
    .sign(key);
}
export async function decrypt(input: string): Promise<any> {
  const { payload } = await jwtVerify(input, key, {
    algorithms: ['HS256'],
  });
  return payload;
}
