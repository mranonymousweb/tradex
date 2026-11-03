/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable @typescript-eslint/no-explicit-any */
'use server';

import { redirect } from 'next/navigation';
import { revalidatePath } from 'next/cache';
import { getTranslations } from 'next-intl/server';
import { Auth, FormForgetPassword } from '@/types/auth';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';
import { removeSession, setSession } from '@/lib/session';
import { getuser, removeuser, setuser } from '@/lib/user';

/*
 * Action Sign up
 */
export const signUpForm = async (state: any, values: Auth) => {
    const t = await getTranslations('errors');

    try {
        const res = await apiFetch('/auth/sign-up', {
            method: 'POST',
            body: JSON.stringify(values),
        });
        const data = await res.json();

        if (!res.ok) {
            return { error: ValidationsError(res, data) };
        }
        await setuser(data?.data?.user);

        return {
            success: true,
            verifyEmail: data?.data?.emailVerificationRequired
        };

    } catch (err) {
        return handleApiError(err, {
            NO_CONNECTION: t('no_connection'),
            TIMEOUT: t('timeout'),
            UNAUTHORIZED: t('unauthorized'),
            SERVER_ERROR: t('server_error'),
            CLIENT_ERROR: t('client_error'),
            UNKNOWN: t('unknown'),
        });
    }
};

/*
 * Action Login
 */
export const loginForm = async (state: any, values: Auth) => {
    const t = await getTranslations('errors');

    try {
        const res = await apiFetch('/auth/sign-in', {
            method: 'POST',
            body: JSON.stringify(values),
        });
        const data = await res.json();
        if (!res.ok) {
            return { error: ValidationsError(res, data) };
        }
        await setuser(data?.data);
        return { success: true };

    } catch (err) {
        return handleApiError(err, {
            NO_CONNECTION: t('no_connection'),
            TIMEOUT: t('timeout'),
            UNAUTHORIZED: t('unauthorized'),
            SERVER_ERROR: t('server_error'),
            CLIENT_ERROR: t('client_error'),
            UNKNOWN: t('unknown'),
        });
    }
};

/*
 * Action Recent code
 */
export const recentCode = async (state: any) => {
    const { user } = await getuser();
    const t = await getTranslations('errors');

    try {
        const res = await apiFetch('/auth/send-otp', {
            method: 'POST',
            body: JSON.stringify({ userId: user.id }),
        });
        const data = await res.json();
        if (!res.ok) {
            return { error: ValidationsError(res, data) };
        }
        return { success: true };
    } catch (err) {
        return handleApiError(err, {
            NO_CONNECTION: t('no_connection'),
            TIMEOUT: t('timeout'),
            UNAUTHORIZED: t('unauthorized'),
            SERVER_ERROR: t('server_error'),
            CLIENT_ERROR: t('client_error'),
            UNKNOWN: t('unknown'),
        });
    }
};

export const recentCodeRegister = async (state: any) => {
    const { user } = await getuser();
    const t = await getTranslations('errors');

    try {
        const res = await apiFetch('/auth/send_register_otp', {
            method: 'POST',
            body: JSON.stringify({ userId: user.id }),
        });
        const data = await res.json();
        if (!res.ok) {
            return { error: ValidationsError(res, data) };
        }
        return { success: true };
    } catch (err) {
        return handleApiError(err, {
            NO_CONNECTION: t('no_connection'),
            TIMEOUT: t('timeout'),
            UNAUTHORIZED: t('unauthorized'),
            SERVER_ERROR: t('server_error'),
            CLIENT_ERROR: t('client_error'),
            UNKNOWN: t('unknown'),
        });
    }
};


export const verify = async (state: any, values: any) => {
    const t = await getTranslations('errors');
    try {
        const res = await apiFetch('/auth/verify', {
            method: 'POST',
            body: JSON.stringify(values),
        });
        const data = await res.json();
        if (!res.ok) {
            return { error: ValidationsError(res, data) };
        }
        await setSession(data?.data.token);
    } catch (err) {
        return handleApiError(err, {
            NO_CONNECTION: t('no_connection'),
            TIMEOUT: t('timeout'),
            UNAUTHORIZED: t('unauthorized'),
            SERVER_ERROR: t('server_error'),
            CLIENT_ERROR: t('client_error'),
            UNKNOWN: t('unknown'),
        });
    }
    redirect('/');
};

/*
 * Action Forget Password
 */
export const forgetPassword = async (
    state: any,
    formData: FormForgetPassword,
) => {
    const t = await getTranslations('errors');

    try {
        const res = await apiFetch('/auth/forget-password', {
            method: 'POST',
            body: JSON.stringify(formData),
        });
        const data = await res.json();
        if (!res.ok) {
            return { error: ValidationsError(res, data) };
        }

        return {
            success: true,
            data: data?.message,
            username: formData.username,
        };
    } catch (err) {
        return handleApiError(err, {
            NO_CONNECTION: t('no_connection'),
            TIMEOUT: t('timeout'),
            UNAUTHORIZED: t('unauthorized'),
            SERVER_ERROR: t('server_error'),
            CLIENT_ERROR: t('client_error'),
            UNKNOWN: t('unknown'),
        });
    }
};

/*
 * Action Recent Code  in the Forget Password
 */
export const recentCodeResetPassword = async (state: any, userId: any) => {
    const t = await getTranslations('errors');

    try {
        const res = await apiFetch('/auth/send-otp', {
            method: 'POST',
            body: JSON.stringify({ userId }),
        });
        const data = await res.json();
        if (!res.ok) {
            return { error: ValidationsError(res, data) };
        }
        return { success: true };
    } catch (err) {
        return handleApiError(err, {
            NO_CONNECTION: t('no_connection'),
            TIMEOUT: t('timeout'),
            UNAUTHORIZED: t('unauthorized'),
            SERVER_ERROR: t('server_error'),
            CLIENT_ERROR: t('client_error'),
            UNKNOWN: t('unknown'),
        });
    }
};

/*
 * Action Logout Account
 */
export const logout = async (state: unknown) => {
    const t = await getTranslations('errors');

    try {
        const res = await apiFetch('/auth/sign-out', {
            method: 'POST',
        });
        const data = await res.json();
        if (!res.ok) {
            return { error: ValidationsError(res, data) };
        }
        await removeSession();
        await removeuser();
    } catch (err) {
        return handleApiError(err, {
            NO_CONNECTION: t('no_connection'),
            TIMEOUT: t('timeout'),
            UNAUTHORIZED: t('unauthorized'),
            SERVER_ERROR: t('server_error'),
            CLIENT_ERROR: t('client_error'),
            UNKNOWN: t('unknown'),
        });
    }
    redirect('/signin');
};

/*
 * Action User Authentication
 */
export const userAuthentication = async (state: any, values: any) => {
    const t = await getTranslations('errors');

    try {
        const res = await apiFetch('/auth/authentication', {
            method: 'POST',
            body: values,
            timeout: 180_000,
        });
        const data = await res.json();
        // if (!res.ok) {
        //     return { error: ValidationsError(res, data) };
        // }
        revalidatePath('/authentication');
        return {
            success: true,
            currentStep: data?.package?.id,
            message: data.message
        };
    } catch (err) {
        return handleApiError(err, {
            NO_CONNECTION: t('no_connection'),
            TIMEOUT: t('timeout'),
            UNAUTHORIZED: t('unauthorized'),
            SERVER_ERROR: t('server_error'),
            CLIENT_ERROR: t('client_error'),
            UNKNOWN: t('unknown'),
        });
    }
};

/*
 * Action Get Document Verify
 */
export const getDocumentVerify = async () => {
    const res = await apiFetch('/auth/kyc/step2/start', {
        method: 'POST',
    });
    const data = await res.json();
    if (!res.ok) {
        throw new Error(data?.message);
    }
    return data?.data;
};

export const getCheckAuth = async (reference_id: string) => {
    const res = await apiFetch(`/auth/kyc/step2/check?reference_id=${reference_id}`, {
        method: 'GET',
    });
    const data = await res.json();
    if (!res.ok) {
        throw new Error(data?.message);
    }
    return data?.data;
};