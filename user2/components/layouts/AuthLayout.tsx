'use client';

import { recaptchaKey } from '@/app/(auth)/config';
import { GoogleReCaptchaProvider } from 'react-google-recaptcha-v3';

interface Props {
    children: React.ReactNode;
}

export default function AuthLayout({ children }: Props) {
    return (
        <GoogleReCaptchaProvider reCaptchaKey={recaptchaKey ?? 'NOT DEFINED'}>
            {children}
        </GoogleReCaptchaProvider>
    );
}
