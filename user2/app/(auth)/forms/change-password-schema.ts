import { T } from '@/types';
import { z } from 'zod';
import { getPasswordSchema } from './password-schema';

export const getChangePasswordSchema = (t: T) => {
  return z
    .object({
      newPassword: getPasswordSchema(t),
      confirmPassword: z.string(),
    })
    .refine((data) => data.newPassword === data.confirmPassword, {
      message: 'Passwords do not match.',
      path: ['confirmPassword'],
    });
};

export type ChangePasswordSchemaType = z.infer<
  ReturnType<typeof getChangePasswordSchema>
>;

//export const getChangePasswordApiSchema = () => {
//  const t = useTranslations("auth");
//  return z.object({
//    token: z.string().nonempty({
//      message: 'A valid token is required to change the password.',
//    }),
//    newPassword: getPasswordSchema(t),
//  });
//};
//
//export type ChangePasswordApiSchemaType = z.infer<
//  ReturnType<typeof getChangePasswordApiSchema>
//>;
