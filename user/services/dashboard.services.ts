'use server';

import { apiFetch } from '@/lib/api';

export const packages = async () => {
  try {
    const res = await apiFetch('/dashboard/packages', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch dashboard packages');
    }
    const data = await res.json();
    return data?.data?.packages;
  } catch (error) {
    console.log(error);
  }
};

export const sliders = async () => {
  try {
    const res = await apiFetch('/dashboard/slides', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch dashboard slides');
    }
    const data = await res.json();
    return data?.data?.slides;
  } catch (error) {
    console.log(error);
  }
};

export const charts = async () => {
  try {
    const res = await apiFetch('/dashboard/charts', {
      method: 'GET',
    });
    if (!res.ok) {
      throw new Error('Failed to fetch dashboard charts');
    }
    const data = await res.json();
    return data?.data;
  } catch (error) {
    console.log(error);
  }
};
