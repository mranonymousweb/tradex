'use server';

import { cookies } from 'next/headers';
import { decrypt, encrypt } from './hash-alg';
import {
    getCookieDomain,
    getSecureCookieOptions,
    getSessionMaxAgeSeconds,
} from './cookie-options';

interface argument {
    id: number;
    email: string;
    mobile: string;
    iranian: boolean;
    google_2fa_is_enabled: boolean;
    confirm_mobile_with_email: "active" | "inactive";
}

export const setuser = async (value: argument) => {
    const user = {
        id: value.id,
        email: value.email,
        mobile: value.mobile,
        iranian: value.iranian,
        confirm_mobile_with_email: value.confirm_mobile_with_email,
        google_2fa_is_enabled: value.google_2fa_is_enabled,
    };
    const session = await encrypt({ user });
    const cookieStore = await cookies();
    const maxAge = getSessionMaxAgeSeconds();

    cookieStore.set({
        name: 'user_info',
        value: session,
        ...getSecureCookieOptions(maxAge),
    });
};

export const getuser = async () => {
    const user = (await cookies()).get('user_info')?.value;
    if (!user) return null;
    try {
        return await decrypt(user);
    } catch {
        return null;
    }
};

export const removeuser = async () => {
    const cookieStore = await cookies();
    cookieStore.delete({
        name: 'user_info',
        domain: getCookieDomain(),
        path: '/',
    });
};
