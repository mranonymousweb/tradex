import { T } from '@/types';
import { z } from 'zod';

export const getPasswordSchema = (t: T, minLength = 6) => {
  return z
    .string()
    .min(minLength, {
      message: t('validations.password'),
    })
    .regex(/[A-Z]/, {
      message: t('validations.password_regex_1'),
    })
    .regex(/[a-z]/, {
      message: t('validations.password_regex_2'),
    })
    .regex(/\d/, {
      message: t('validations.password_regex_3'),
    })
    .regex(/[!@#$%^&*(),.?":{}|<>]/, {
      message: t('validations.password_regex_4'),
    });
};
