/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable @typescript-eslint/no-unused-vars */
import { z } from 'zod';

export const getVerifyCodeSchema = (t: any) => {
  return z.object({
    userId: z.string(),
    code: z
      .string()
      .min(6, { message: 'حداقل باید 6 عدد باشد' })
      .max(6, { message: 'حداکثر باید 6 عدد باشد' })
      .refine((val) => !isNaN(Number(val)), {
        message: 'مقدار عددی معتبر نیست',
      })
      .transform((val) => Number(val)),
  });
};

export type VerifyCodeSchemaType = z.infer<
  ReturnType<typeof getVerifyCodeSchema>
>;
