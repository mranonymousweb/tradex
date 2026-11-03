import { T } from '@/types';
import { z } from 'zod';
import { getPasswordSchema } from './password-schema';

export const getSignupSchema = (t: T) => {
  return z.object({
    firstName: z.string().min(1, { message: t('validations.username') }),
    lastName: z.string().min(1, { message: t('validations.password') }),
    mobile: z.string().min(1, { message: t('validations.mobile') }),
    countryCode: z.string(),
    refId: z.string(),
    password: getPasswordSchema(t),
    email: z
      .string()
      .email({ message: t('validations.email_invalid') })
      .min(1, { message: t('validations.email_required') }),
    accept: z.boolean().refine((val) => val === true, {
      message: t('validations.accept_rule'),
    }),
  });
};

export type SignupSchemaType = z.infer<ReturnType<typeof getSignupSchema>>;
