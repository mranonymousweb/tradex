import { T } from '@/types';
import { z } from 'zod';

export const FormSchema = (t: T) => {
  return z.object({
    name: z.string().min(1, { message: t('validation.name') }),
    family: z.string().min(1, { message: t('validation.family') }),
    national_code: z
      .string()
      .min(1, { message: t('validation.national_code') }),
    birthday: z.any(),
    ip_address: z.string().optional(),
    accept_terms: z.boolean().refine((value) => value, {
      message: t('validation.accept_terms'),
    }),
    accept_rules: z.boolean().refine((value) => value, {
      message: t('validation.accept_rules'),
    }),
  });
};

export type FormSchemaType = z.infer<ReturnType<typeof FormSchema>>;
