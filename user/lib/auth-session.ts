import type { NextResponse } from 'next/server';
import { getCookieDomain } from './cookie-options';
import { removeSession } from './session';
import { removeuser } from './user';

export const AUTH_LOGOUT_PATH = '/api/auth/logout';

const AUTH_COOKIE_NAMES = ['user_session', 'user_info'] as const;

/** Clear auth cookies on an outgoing response (Route Handlers / Middleware). */
export function clearAuthCookiesOnResponse(response: NextResponse): NextResponse {
    const domain = getCookieDomain();

    for (const name of AUTH_COOKIE_NAMES) {
        response.cookies.delete({ name, domain, path: '/' });
    }

    return response;
}

/** Clear auth cookies in Server Actions. */
export async function clearAuthSession(): Promise<void> {
    await Promise.all([removeSession(), removeuser()]);
}
