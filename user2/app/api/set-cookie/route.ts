import { redirect } from 'next/navigation';
import { NextResponse } from 'next/server';
import { setSession } from '@/lib/session';

const SET_COOKIE_MAX_AGE_SECONDS = 28 * 60;

export async function GET(req: Request) {
    const { searchParams } = new URL(req.url);
    const token = searchParams.get('token');

    if (!token) {
        return NextResponse.json({ message: 'Token is required' }, { status: 400 });
    }

    await setSession(token, SET_COOKIE_MAX_AGE_SECONDS);
    redirect('/');
}
