/* eslint-disable @typescript-eslint/no-explicit-any */
'use server';

import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';

export const banks = async () => {
  try {
    const res = await apiFetch('/card/bank', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch card bank');
    }
    const data = await res.json();
    return data?.data;
  } catch (err) {
    console.log(err);
  }
};

export const Cardcurrency = async () => {
  try {
    const res = await apiFetch('/card/currency', {
      method: 'GET',
    });

    if (!res.ok) {
      throw new Error('Failed to fetch card currency');
    }
    const data = await res.json();
    return data?.data;
  } catch (err) {
    console.log(err);
  }
};

export const addCardBank = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/card/store', {
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

export const listBanks = async (index?: number) => {
  try {
    const res = await apiFetch(`/card/list?page=${index}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch card list');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const toggleStatus = async (state: any, formData: FormData) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/card/change/status', {
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
