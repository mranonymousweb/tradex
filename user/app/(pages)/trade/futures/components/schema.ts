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
            enter_price: z.string().optional(),
            enter_balance: z.string().optional(),
            has_tpsl: z.boolean(),
            take_profit: z.string().optional(),
            stop_loss: z.string().optional(),
            stop_limit: z.string().optional(),
            stop_market: z.string().optional(),
            quantity: z
                .string({
                    required_error: t('required_field_amount'),
                    invalid_type_error: t('required_field_amount'),
                })
                .min(1, { message: t('required_field_amount') }),
        })
        .superRefine((data, ctx) => {
            if (data.type === FormType.StopLimit && !data.stop_limit) {
                ctx.addIssue({
                    code: 'custom',
                    path: ['stop_limit'],
                    message: t('required_field_stp'),
                });
            }
            if (data.type === FormType.StopMarket && !data.stop_market) {
                ctx.addIssue({
                    code: 'custom',
                    path: ['stop_market'],
                    message: t('required_field_stp'),
                });
            }
            if (
                [FormType.Limit, FormType.StopLimit].includes(data.type) &&
                !data.enter_price
            ) {
                ctx.addIssue({
                    code: 'custom',
                    path: ['enter_price'],
                    message: t('required_field_price'),
                });
            }
        });

export type FormSchemaType = z.infer<ReturnType<typeof FormSchema>>;
