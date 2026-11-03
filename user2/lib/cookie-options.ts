export function getCookieDomain(): string {
    return `.${process.env.NEXT_PUBLIC_DOMAIN_PANEL}`;
}

export function isSecureCookieEnv(): boolean {
    return process.env.MODE_ENV === 'production';
}

export function getSessionMaxAgeSeconds(): number {
    const hours = Number(process.env.HOURS_PER_DAY) || 168;
    return hours * 60 * 60;
}

export function getSecureCookieOptions(maxAgeSeconds: number) {
    return {
        domain: getCookieDomain(),
        httpOnly: true,
        path: '/',
        sameSite: 'lax' as const,
        maxAge: maxAgeSeconds,
        secure: isSecureCookieEnv(),
    };
}
