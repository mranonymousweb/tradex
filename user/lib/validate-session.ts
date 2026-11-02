import { decrypt } from './hash-alg';

export async function isValidSessionToken(
  sessionCookie: string | undefined,
): Promise<boolean> {
  if (!sessionCookie) return false;

  try {
    const payload = await decrypt(sessionCookie);
    if (!payload?.user?.token) return false;

    if (payload.expires) {
      const expires = new Date(payload.expires as string | number);
      if (!Number.isNaN(expires.getTime()) && expires < new Date()) {
        return false;
      }
    }

    return true;
  } catch {
    return false;
  }
}
