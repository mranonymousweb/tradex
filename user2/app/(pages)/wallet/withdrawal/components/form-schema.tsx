import { T } from '@/types';
import { z } from 'zod';

export const FormSchema = (isIRT: boolean, isCurrency: boolean, t: T) =>
  z
    .object({
      currencyId: z
        .string()
        .min(1, { message: t('validation.choose_currency') }),
      networkId: z.string().optional().nullable(),
      cardId: z.string().optional().nullable(),
      amount: z
        .string()
        .min(1, { message: t('validation.withdrawal_amount') })
        .optional(),
      address: z.string().optional(),
      tag: z.string().optional(),
    })
    .superRefine((data, ctx) => {
      if (isIRT) {
        if (!data.cardId) {
          ctx.addIssue({
            code: 'custom',
            path: ['bankId'],
            message: t('validation.choose_type_bank'),
          });
        }
        return;
      }
      if (isCurrency) {
        if (!data.networkId) {
          ctx.addIssue({
            code: 'custom',
            path: ['networkId'],
            message: t('validation.choose_type_network'),
          });
        }

        if (!data.address) {
          ctx.addIssue({
            code: 'custom',
            path: ['address'],
            message: t('validation.choose_address'),
          });
        }
      }
    });

export type FormSchemaType = z.infer<ReturnType<typeof FormSchema>>;
