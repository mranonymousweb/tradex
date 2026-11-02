/* eslint-disable @typescript-eslint/no-explicit-any */
'use server';

import { revalidatePath } from 'next/cache';
import { getTranslations } from 'next-intl/server';
import { apiFetch } from '@/lib/api';
import ValidationsError from '@/lib/exeptions';
import { handleApiError } from '@/lib/handleApiError';
import { getSession } from '@/lib/session';

export const getMessages = async (id: number | string) => {
  try {
    const res = await apiFetch(`/ticket/messages?ticketId=${id}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch ticket message');
    }
    const data = await res.json();
    return data?.data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const getTickets = async (path?: string) => {
  const token = await getSession();
  if (!token) return null;

  try {
    const res = await apiFetch(`/ticket?${path}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch ticket');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const filterData = async (state: any, formData: any) => {
  try {
    const res = await apiFetch(`/ticket?${formData}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch ticket GET');
    }
    const data = await res.json();
    return {
      success: true,
      search: data?.data,
    };
  } catch (err) {
    console.log(err);
  }
};

export const getDepartments = async () => {
  try {
    const res = await apiFetch(`/ticket/departments`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch ticket department');
    }
    const data = await res.json();
    return data?.data;
  } catch (err) {
    console.log(err);
    throw err;
  }
};

export const addTicket = async (state: any, formData: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch(`/ticket/store`, {
      method: 'POST',
      body: formData,
    });
    const data = await res.json();
    if (!res.ok) {
      return {
        error: ValidationsError(res, data),
        message: data.error.details,
      };
    }

    revalidatePath('/ticket/list');
    return {
      success: true,
      message: data.message,
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

export const sendMessage = async (formData: any) => {
  const t = await getTranslations('errors');

  try {
    const res = await apiFetch(`/ticket/message`, {
      method: 'POST',
      body: formData,
    });
    const data = await res.json();

    if (!res.ok) {
      throw new Error(data?.message || 'Something went wrong');
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
