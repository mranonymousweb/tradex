'use server';

import { apiFetch } from '@/lib/api';

export const metaData = async () => {
  try {
    const res = await apiFetch('/meta/data');
    if (!res.ok) return null;
    const data = await res.json();
    return data?.data ?? null;
  } catch (err) {
    return null;
  }
};
