/* eslint-disable @typescript-eslint/no-explicit-any */
'use server';

import { revalidatePath } from 'next/cache';
import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';

export const getWalletCurrencies = async (page?: number, search?: string) => {
  const param = search && `&name=${search}`;
  try {
    const res = await apiFetch(`/wallet/currencies?page=${page}${param}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch wallet currencies');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const receiveAddress = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/wallet/receive', {
      method: 'POST',
      body: JSON.stringify(values),
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }
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

export const receiveDepositType = async (state: any, data: string) => {
  const url = data === 'cards' ? '/card/bank' : '/irt/gateways';

  try {
    const res = await apiFetch(url, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch card bank');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
  }
};

export const depositCurrency = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/wallet/deposit', {
      method: 'POST',
      body: JSON.stringify(values),
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }

    revalidatePath('/wallet');
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

export const deposit = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  const formData = new FormData();
  if (values.amount) formData.append('amount', values.amount);
  if (values.transaction) formData.append('cardId', values.transaction);
  if (values.currencyId) formData.append('currencyId', values.currencyId);
  if (values.file) formData.append('file', values.file);

  const gatewayData = {
    amount: values.amount,
    gateway: values.transaction,
    currencyId: values.currencyId,
    type: values.currencyType,
  };
  const CurrencyData = {
    txId: values.txId,
    networkId: values.networkId,
    currencyId: values.currencyId,
    type: values.currencyType,
  };
  const voucherData = {
    txId: values.txId,
    currencyId: values.currencyId,
    tag: values.tag,
    type: values.currencyType,
  };

  const sendData = () => {
    if (values.currencyType === 1) {
      return {
        data: JSON.stringify(CurrencyData),
        url: '/wallet/deposit',
      };
    } else if (values.currencyType === 3) {
      return {
        data: JSON.stringify(voucherData),
        url: '/wallet/deposit',
      };
    } else {
      if (values.type === 'card') {
        return {
          data: formData,
          url: '/irt/card',
        };
      } else if (values.type === 'gateway') {
        return {
          data: JSON.stringify(gatewayData),
          url: '/irt/request',
        };
      }
    }
  };
  const dataFormt = sendData();
  const isGateWay = values.currencyType === 2 && values.type === 'gateway';

  try {
    const res = await apiFetch(String(dataFormt?.url), {
      method: 'POST',
      body: dataFormt?.data,
    });
    const datas = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, datas) };
    }

    return {
      success: true,
      isGateWay,
      url: datas?.data,
      message: datas?.message,
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

export const withdrawal = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/wallet/withdraw', {
      method: 'POST',
      body: JSON.stringify(values),
    });

    const data = await res.json();

    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }

    const spot = data?.data?.asset?.balance ?? 0;
    const futures = data?.data?.asset?.feature_balance ?? 0;
    const block = data?.data?.asset?.block_balance ?? 0;
    const total = Number(spot) + Number(futures) + Number(block);

    return {
      success: true,
      spot,
      total,
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

export const filterData = async (state: any, formData: any) => {
  try {
    const res = await apiFetch(`/wallet/transactions?${formData}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch wallet transactions');
    }
    const data = await res.json();
    return { success: true, search: data?.data };
  } catch (err) {
    console.log(err);
  }
};

export const walletTransactions = async (url: string) => {
  try {
    const res = await apiFetch(`/wallet/transactions?${url}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch wallet transactions');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const userAssets = async (symbol: string) => {
  try {
    const res = await apiFetch(`/wallet/assets?symbol=${symbol}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch wallet assets symbol');
    }
    const data = await res.json();
    return data?.data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const verifyVoucher = async (state: any, values: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/otp/code/verify', {
      method: 'POST',
      body: JSON.stringify(values),
    });
    const data = await res.json();

    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }
    return {
      success: data?.data.is_valid,
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

// Wallet Assets
export const assetsMe = async () => {
  try {
    const res = await apiFetch('/wallet/assets', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch wallet assets');
    }
    const data = await res.json();
    return data?.data;
  } catch (error) {
    console.log(error);
  }
};

//Wallet Futures
export const transferAsset = async (state: any, formData: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch('/wallet/swap/balance', {
      method: 'POST',
      body: JSON.stringify(formData),
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: ValidationsError(res, data) };
    }
    revalidatePath('/wallet/futures');
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
//History Transaction Futures
export const historyTransactionFutures = async (page: number) => {
  try {
    const res = await apiFetch(`/wallet/asset/history?page=${page}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch wallet asset history');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};
