import { T } from '@/types';
import z from 'zod';
import { FormType } from './constants';

export const FormSchema = (t: T) =>
  z
    .object({
      base_currency: z.string(),
      main_currency: z.string(),
      side: z.number(),
      type: z.number(),
      price: z.string().optional(),
      stop_price: z.string().optional(),
      type_amount: z.number(),
      amount: z
        .string({
          required_error: t('required_amount'),
          invalid_type_error: t('required_amount'),
        })
        .min(1, { message: t('required_amount') }),
    })
    .superRefine((data, ctx) => {
        if ([3, 5].includes(data.type) && !data.stop_price) {
            ctx.addIssue({
                code: 'custom',
                path: ['stop_price'],
                message: t('required_stop_price'),
            });
        }
        if ([FormType.Limit, FormType.StopLimit].includes(data.type) && !data.price) {
            ctx.addIssue({
            code: 'custom',
            path: ['price'],
            message: t('required_price'),
            });
        }
    });

export type FormSchemaType = z.infer<ReturnType<typeof FormSchema>>;
