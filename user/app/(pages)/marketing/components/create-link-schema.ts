import z from 'zod';

export const FormCreate = () => {
  return z.object({
    name: z.string().min(1, { message: 'name required' }),
    user_percentage: z.string().min(1, { message: 'percentage required' }),
    is_active: z.any(),
    id: z.any(),
  });
};

export type FormCreateSchemaType = z.infer<ReturnType<typeof FormCreate>>;
