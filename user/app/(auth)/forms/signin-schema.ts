import { T } from '@/types';
import { z } from 'zod';

export const getSigninSchema = (t: T) => {
  return z.object({
    username: z.string().min(1, { message: t('validations.username') }),
    password: z
      .string()
      .min(6, { message: t('validations.password') })
      .min(1, { message: t('validations.password_required') }),
  });
};

export type SigninSchemaType = z.infer<ReturnType<typeof getSigninSchema>>;
