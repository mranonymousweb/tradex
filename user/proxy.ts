import { NextResponse } from 'next/server';
import type { NextRequest } from 'next/server';
import { isGuestAccessiblePath } from '@/lib/guest-routes';
import { isValidSessionToken } from '@/lib/validate-session';

const PUBLIC_PATHS = [
  '/signin',
  '/signup',
  '/verify-account',
  '/verify-email-phone',
  '/reset-password',
  '/change-password',
];

function isPublicPath(pathname: string): boolean {
  return (
    PUBLIC_PATHS.some(
      (path) => pathname === path || pathname.startsWith(`${path}/`),
    ) || isGuestAccessiblePath(pathname)
  );
}

function shouldSkipAuth(pathname: string): boolean {
  return (
    isPublicPath(pathname) ||
    pathname.startsWith('/api/') ||
    pathname.startsWith('/_next/') ||
    pathname.startsWith('/chart/') ||
    pathname === '/favicon.ico' ||
    /\.[a-zA-Z0-9]+$/.test(pathname)
  );
}

function nextWithPathname(request: NextRequest, pathname: string) {
  const requestHeaders = new Headers(request.headers);
  requestHeaders.set('x-pathname', pathname);
  return NextResponse.next({ request: { headers: requestHeaders } });
}

export async function proxy(request: NextRequest) {
  const { pathname } = request.nextUrl;

  if (shouldSkipAuth(pathname)) {
    return nextWithPathname(request, pathname);
  }

  const sessionCookie = request.cookies.get('user_session')?.value;
  if (!(await isValidSessionToken(sessionCookie))) {
    const signInUrl = new URL('/signin', request.url);
    if (pathname !== '/') {
      signInUrl.searchParams.set('redirect', pathname);
    }
    return NextResponse.redirect(signInUrl);
  }

  return nextWithPathname(request, pathname);
}

export const config = {
  matcher: ['/((?!_next/static|_next/image).*)'],
};
