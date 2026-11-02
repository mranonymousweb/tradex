type RecaptchaVerifyResult =
    | { ok: true }
    | { ok: false; reason: 'request_failed' | 'verification_failed' };

export async function verifyRecaptchaToken(
    gReCaptchaToken: string,
): Promise<RecaptchaVerifyResult> {
    try {
        const response = await fetch('/api/verify-captcha', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ gReCaptchaToken }),
        });

        const data = await response.json();

        if (response.ok && data.status === 'success') {
            return { ok: true };
        }

        return { ok: false, reason: 'verification_failed' };
    } catch {
        return { ok: false, reason: 'request_failed' };
    }
}
