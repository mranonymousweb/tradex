import { T } from '@/types';
import { z } from 'zod';

export const FormSchema = (isIRT: boolean, isCurrency: boolean, t: T) =>
  z
    .object({
      currencyId: z
        .string()
        .min(1, { message: t('validation.choose_currency') }),
      networkId: z.string().optional().nullable(),
      txId: z.string().optional().nullable(),
      type: z.string().optional(),
      tag: z.string().optional().nullable(),
      transaction: z.string().optional(),
      amount: z.string().optional(),
      file: z.any().optional(),
      currencyType: z.number(),
    })
    .superRefine((data, ctx) => {
      if (isIRT) {
        if (!data.amount) {
          ctx.addIssue({
            code: 'custom',
            path: ['amount'],
            message: t('validation.deposit_amount'),
          });
        }
        if (!data.type) {
          ctx.addIssue({
            code: 'custom',
            path: ['type'],
            message: t('validation.choose_type_deposit'),
          });
        }
        if (!data.transaction) {
          ctx.addIssue({
            code: 'custom',
            path: ['transaction'],
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
        if (data.networkId && !data.txId) {
          ctx.addIssue({
            code: 'custom',
            path: ['txId'],
            message: t('validation.choose_transaction'),
          });
        }
      }
    });

export type FormSchemaType = z.infer<ReturnType<typeof FormSchema>>;
