import { T } from '@/types';
import { z } from 'zod';

export const FormSchema = (t: T) => {
  return z.object({
    bank_id: z.string().min(1, { message: t('validation.choose_type_bank') }),
    currency_id: z
      .string()
      .min(1, { message: t('validation.choose_currency') }),
    card_number: z
      .string()
      .min(1, { message: t('validation.choose_card_number') }),
    iban: z.string().optional(),
  });
};

export type FormSchemaType = z.infer<ReturnType<typeof FormSchema>>;
