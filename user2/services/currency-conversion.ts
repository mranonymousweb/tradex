'use server';

import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';

export const currencyConversions = async () => {
  try {
    const res = await apiFetch('/wallet/converted-inventory', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch  wallet converted-inventory');
    }
    const data = await res.json();
    return data?.data;
  } catch (error) {
    console.log(error);
  }
};

export const logsConversion = async () => {
  try {
    const res = await apiFetch('/wallet/converted-inventory-logs', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch wallet converted-inventory logs');
    }
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
  }
};

export const setConversion = async (state: any, formData: FormData) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/wallet/converted-inventory', {
      method: 'POST',
      body: formData,
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
