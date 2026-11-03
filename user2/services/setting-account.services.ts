'use server';

import { revalidatePath } from 'next/cache';
import { redirect } from 'next/navigation';
import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';
import { removeSession } from '@/lib/session';

export const updateAvatar = async (state: any, formData: FormData) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch(`/dashboard/profile`, {
      method: 'POST',
      body: formData,
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }

    revalidatePath('settings-account');
    return {
      success: true,
      message: data?.message,
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

export const devicesLog = async () => {
  try {
    const res = await apiFetch(`/device`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch device');
    }
    const data = await res.json();
    return data?.data?.devices;
  } catch (err) {
    console.log(err);
  }
};

export const destroyDevices = async (state: any, formData: FormData) => {
  const id = formData.get('id');
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch(`/device/destroy?id=${id}`, {
      method: 'DELETE',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch destroy');
    }
    const data = await res.json();

    revalidatePath('settings-account');
    return {
      success: true,
      message: data?.message,
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

export const sendOtp = async (state: any, formData: FormData) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/auth/otp-by-section', {
      method: 'POST',
      body: formData,
    });
    if (!res.ok) {
      throw new Error('Failed to fetch auth otp send');
    }
    const data = await res.json();
    return {
      success: true,
      message: data,
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

export const updateFieldProfile = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/dashboard/credential', {
      method: 'POST',
      body: JSON.stringify(values),
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }
    await removeSession();
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

//Google Authentication
export const getKeyGoogleAuth = async () => {
  try {
    const res = await apiFetch('/google/auth/create', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch google auth create');
    }
    const data = await res.json();
    return data?.data;
  } catch (error) {
    console.log(error);
    throw error;
  }
};

export const toggleGoogleAuth = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/google/auth/enabled', {
      method: 'POST',
      body: JSON.stringify(values),
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }
    revalidatePath('settings-account');
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
