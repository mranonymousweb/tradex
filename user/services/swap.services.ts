'use server';

import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';

export const getSwapCurrency = async (page?: number, filters?: string) => {
  const param = filters && `&search=${filters}`;
  try {
    const res = await apiFetch(`/swap/currencies?page=${page}${param}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch swap currencies');
    }
    const data = await res.json();
    return data?.data;
  } catch (error) {
    console.log(error);
  }
};

export const createSwap = async (state: any, formData: FormData) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/swap', {
      method: 'POST',
      body: formData,
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }
    return {
      data,
      success: true,
      balance: data?.data,
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

export const listOrdersSwap = async (path: string) => {
  try {
    const res = await apiFetch(`/swap/index?${path}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch swap currencies');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};
