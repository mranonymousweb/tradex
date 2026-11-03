'use server';

import { apiFetch } from '@/lib/api';

export const getCurrencies = async (path?: string) => {
  try {
    const res = await apiFetch(`/currency/list?${path}`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch currency list');
    }
    const data = await res.json();
    return data;
  } catch (err) {
    console.log(err);
  }
};
