'use server';

import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';

export const CurrenciesStock = async () => {
  try {
    const res = await apiFetch('/staking/currency', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch stacking currency');
    }
    const data = await res.json();
    return data?.data;
  } catch (err) {
    console.log(err);
  }
};

export const createStock = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/staking/create', {
      method: 'POST',
      body: JSON.stringify(values),
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

export const myStocks = async () => {
  try {
    const res = await apiFetch('/staking/list', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch stackin list');
    }
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
    throw error;
  }
};

export const rejectStock = async (state: any, formData: FormData) => {
  const id = formData.get('id');
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch(`/staking/cancel?id=${id}`, {
      method: 'GET',
    });
    if (!res.ok) {
      const data = await res.json();
      return {
        error: data.message,
      };
    }
    const data = await res.json();
    return {
      success: true,
      message: data?.message,
    };
  } catch (err) {
    const result = handleApiError(err, {
      NO_CONNECTION: t('no_connection'),
      TIMEOUT: t('timeout'),
      UNAUTHORIZED: t('unauthorized'),
      SERVER_ERROR: t('server_error'),
      CLIENT_ERROR: t('client_error'),
      UNKNOWN: t('unknown'),
    });
    return { error: result.message };
  }
};
