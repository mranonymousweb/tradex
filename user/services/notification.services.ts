/* eslint-disable @typescript-eslint/no-explicit-any */
'use server';

import { revalidatePath } from 'next/cache';
import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';
import { getSession } from '@/lib/session';

export const getNotifications = async (
  page?: number | string,
  section?: string,
) => {
  const token = await getSession();
  if (!token) return null;

  try {
    const res = await apiFetch(`/notifications/index?page=${page}${section}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch notification index');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const getNotificationsPopUp = async () => {
  const token = await getSession();
  if (!token) return null;

  try {
    const res = await apiFetch(`/notifications/index?section=pop_up`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch notification popup');
    }
    const data = await res.json();
    return data?.data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const getNotificationsTape = async () => {
  const token = await getSession();
  if (!token) return null;

  try {
    const res = await apiFetch(`/notifications/index?section=tape`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch notification tage');
    }
    const data = await res.json();
    return data?.data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const updateNotification = async (state: any, formData: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch(`/notifications/update`, {
      method: 'POST',
      body: formData,
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
