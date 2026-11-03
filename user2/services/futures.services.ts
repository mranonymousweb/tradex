/* eslint-disable @typescript-eslint/no-explicit-any */
'use server';

import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';
import { getSession } from '@/lib/session';

export const getMarketBase = async () => {
  try {
    const res = await apiFetch('/future/currency/base', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch future currency');
    }
    const data = await res.json();
    return data?.data;
  } catch (error) {
    console.log(error);
  }
};

export const getCurrencyList = async (
  page: number,
  symbol: string,
  search: string,
) => {
  try {
    const res = await apiFetch(
      `/future/currency/list?base_currency_symbol=${symbol}&page=${page}&main_currency_symbol=${search}`,
      {
        method: 'GET',
      },
    );
    if (!res.ok) {
      throw new Error('Fialed to fetch currency list');
    }
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
    throw error;
  }
};

export const listFavoriteFutures = async () => {
  const token = await getSession();
  if (!token) return;

  try {
    const res = await apiFetch('/market/currency/favorite/list?type=2', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch market currency favorite list');
    }
    const data = await res.json();
    return data?.data;
  } catch (error) {
    console.log(error);
  }
};

export const updateFavorite = async (formData: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch(`/market/currency/favorite${formData.url}`, {
      method: 'POST',
      body: JSON.stringify(formData.data),
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }
    return data?.data ? true : false;
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

export const getOptions = async () => {
  try {
    const res = await apiFetch('/future/option', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch future option');
    }
    const data = await res.json();
    return data?.data;
  } catch (error) {
    console.log(error);
    throw error;
  }
};

export const updateLeverage = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/future/update/leverage', {
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

export const updateMarginMode = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/future/update/margin/mode', {
      method: 'POST',
      body: JSON.stringify(values),
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

export const addOrder = async (state: any, formData: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/future/order/create', {
      method: 'POST',
      body: JSON.stringify(formData),
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

export const closeOrder = async (state: any, formData: FormData) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/future/order/close', {
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

export const getOrderHistory = async (value: string) => {
  try {
    const res = await apiFetch(`/future/order/history?${value}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch future order history');
    }
    const data = await res.json();
    return data;
  } catch (error) {
    console.log(error);
    throw error;
  }
};

export const updateTpsl = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/future/order/update', {
      method: 'POST',
      body: JSON.stringify(values),
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

export const ordersMarketFutures = async (path: string) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch(`/future/order?pairs=${path}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch future order market');
    }
    const data = await res.json();
    return data;
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
