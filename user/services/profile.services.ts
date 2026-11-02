'use server';

import { apiFetch } from '@/lib/api';
import { getSession } from '@/lib/session';

export const getProfileInfo = async () => {
  const token = await getSession();
  if (!token) return null;

  try {
    const res = await apiFetch(`/dashboard/profile`, {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch dashboard profile');
    }
    const data = await res.json();
    return data?.data;
  } catch (err) {
    console.log(err);
  }
};
