/* eslint-disable @typescript-eslint/no-explicit-any */
'use server';

import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';

export const getSubset = async (id: string, page: number) => {
  try {
    const res = await apiFetch(
      `/dashboard/marketing/users?marketing_code_id=${id}&page=${page}`,
      {
        method: 'GET',
      },
    );
    if (!res.ok) {
      throw new Error('Failed to fetch users marketing');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const getLogs = async (id: string, page: number) => {
  try {
    const res = await apiFetch(
      `/dashboard/marketing/logs?user_id=${id}&page=${page}`,
      {
        method: 'GET',
      },
    );
    if (!res.ok) {
      throw new Error('Failed to fetch marketing logs');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const getLinks = async (page: number) => {
  try {
    const res = await apiFetch('/dashboard/marketing/codes', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch marketing codes');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const createLink = async (state: any, formData: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch(`/dashboard/marketing`, {
      method: 'POST',
      body: JSON.stringify(formData),
    });

    const data = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }

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
