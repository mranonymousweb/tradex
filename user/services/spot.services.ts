'use server';

import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';
import { getSession } from '@/lib/session';

export const createOrder = async (state: any, formData: any) => {
    const t = await getTranslations('errors');
    try {
        const res = await apiFetch('/market/create', {
            method: 'POST',
            body: JSON.stringify(formData),
        });
        const data = await res.json();
        if (!res.ok) {
            return {
                error: ValidationsError(res, data),
                message: data.message || 'Fialed to request data',
            };
        }

        return {
            success: true,
            message: data?.data?.message,
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

export const getOrderHistory = async (value: string) => {
    try {
        const res = await apiFetch(`/market/history?${value}`, {
            method: 'GET',
        });
        if (!res.ok) {
            throw new Error('Failed to fetch market history');
        }
        const data = await res.json();
        return data;
    } catch (error) {
        console.log(error);
        throw error;
    }
};

export const ordersMarket = async (path: string) => {
    try {
        const res = await apiFetch(`/market/user/order?${path}`, {
            method: 'GET',
        });
        if (!res.ok) {
            throw new Error('Failed to fetch market user order');
        }
        const data = await res.json();
        return data;
    } catch (error) {
        console.log(error);
        throw error;
    }
};

export const spotBalance = async (pair: string) => {
    const token = await getSession();
    if (!token) return null;

    try {
        const res = await apiFetch(`/wallet/asset/spot?pairs=${pair}`, {
            method: 'GET',
        });
        if (!res.ok) {
            throw new Error('Failed to fetch wallet asset spot');
        }
        const data = await res.json();
        return data?.data;
    } catch (error) {
        console.log(error);
        throw error;
    }
};

export const marketCurrencySpot = async () => {
    try {
        const res = await apiFetch('/market/currency', {
            method: 'GET',
        });
        if (!res.ok) {
            throw new Error('Failed to fetch market currency');
        }
        const data = await res.json();
        return data?.data;
    } catch (error) {
        console.log(error);
    }
};

export const listFavoriteSpot = async () => {
    const token = await getSession();
    if (!token) return;

    try {
        const res = await apiFetch('/market/currency/favorite/list?type=1', {
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

export const getCurrencyBase = async (
    page: number,
    symbol: string,
    search?: string,
) => {
    try {
        const res = await apiFetch(
            `/market/currency/base?base_currency_symbol=${symbol}&page=${page}&main_currency_symbol=${search}`,
            {
                method: 'GET',
            },
        );
        if (!res.ok) {
            throw new Error('Failed to fetch market currency base');
        }
        const data = await res.json();
        return data;
    } catch (error) {
        console.log(error);
        throw error;
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

export const closeOrder = async (state: any, formData: FormData) => {
    const t = await getTranslations('errors');

    try {
        const res = await apiFetch('/market/cancel', {
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
