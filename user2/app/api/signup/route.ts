import { NextRequest, NextResponse } from 'next/server';
import { verifyRecaptchaToken } from '@/lib/recaptcha';

export async function POST(req: NextRequest) {
  try {
    const recaptchaToken = req.headers.get('x-recaptcha-token');

    if (!recaptchaToken) {
      return NextResponse.json(
        { message: 'reCAPTCHA verification required' },
        { status: 400 },
      );
    }

    const isValidToken = await verifyRecaptchaToken(recaptchaToken);

    if (!isValidToken) {
      return NextResponse.json(
        { message: 'reCAPTCHA verification failed' },
        { status: 400 },
      );
    }

    // Parse the request body as JSON.
    //const body = await req.json();

    // Validate the data using safeParse.
    //const result = getSignupSchema().safeParse(body);
    //if (!result.success) {
    //  return NextResponse.json(
    //    {
    //      message: 'Invalid input. Please check your data and try again.',
    //    },
    //    { status: 400 },
    //  );
    //}

    //result.data;

    return NextResponse.json(
      {
        message:
          'Registration successful. Check your email to verify your account.',
      },
      { status: 200 },
    );
  } catch {
    return NextResponse.json(
      { message: 'Registration failed. Please try again later.' },
      { status: 500 },
    );
  }
}
