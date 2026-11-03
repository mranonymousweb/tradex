import { NextRequest, NextResponse } from 'next/server';
import { clearAuthCookiesOnResponse } from '@/lib/auth-session';

function logoutResponse(request: NextRequest) {
  const signInUrl = new URL('/signin', request.url);
  const redirectTo = request.nextUrl.searchParams.get('redirect');

  if (redirectTo?.startsWith('/') && !redirectTo.startsWith('//')) {
    signInUrl.searchParams.set('redirect', redirectTo);
  }

  const response = NextResponse.redirect(signInUrl);
  return clearAuthCookiesOnResponse(response);
}

export async function GET(request: NextRequest) {
  return logoutResponse(request);
}

/** Server Actions follow redirects with POST — handle both methods. */
export async function POST(request: NextRequest) {
  return logoutResponse(request);
}
