'use server';

import { cookies } from 'next/headers';
import { decrypt, encrypt } from './hash-alg';
import {
    getCookieDomain,
    getSecureCookieOptions,
    getSessionMaxAgeSeconds,
} from './cookie-options';

export async function setSession(
    token: string,
    maxAgeSeconds = getSessionMaxAgeSeconds(),
) {
    const cookieStore = await cookies();
    const maxAge = maxAgeSeconds;
    const user = { token, name: 'exchange' };
    const expires = new Date(Date.now() + maxAge * 1000);
    const session = await encrypt({ user, expires });

    cookieStore.set({
        name: 'user_session',
        value: session,
        ...getSecureCookieOptions(maxAge),
    });
}

export async function removeSession() {
    const cookieStore = await cookies();
    cookieStore.delete({
        name: 'user_session',
        domain: getCookieDomain(),
        path: '/',
    });
}

export async function getSession() {
    const session = (await cookies()).get('user_session')?.value;
    if (!session) return null;
    try {
        return await decrypt(session);
    } catch {
        return null;
    }
}
