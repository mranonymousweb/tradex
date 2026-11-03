import {
  Dispatch,
  SetStateAction,
  startTransition,
  useActionState,
  useEffect,
} from 'react';
import { useRouter } from 'next/navigation';
import { zodResolver } from '@hookform/resolvers/zod';
import { AlertCircle, ArrowLeft } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { AlertError } from '@/hooks/alert-error';
import { forgetPassword } from '@/services/auth.services';
import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinners';
import { getPasswordSchema } from '../../forms/password-schema';
import { RecentCodeResetPassword } from './RecentCode';

interface Info {
  step: number;
  username: string;
  mobile: string;
  email: string;
  userId: string;
}
interface Props {
  setUserForm: Dispatch<SetStateAction<Info>>;
  userForm: Info;
}

const Step3: React.FC<Props> = ({ userForm, setUserForm }) => {
  const t = useTranslations('auth');
  const router = useRouter();
  const [state, action, pending] = useActionState(forgetPassword, null);

  useEffect(() => {
    if (state?.success) {
      router.push('/signin');
    }
  }, [state, router]);

  const formSchema = z.object({
    username: z.string(),
    step: z.number(),
    password: getPasswordSchema(t),
    code: z.string().min(1, { message: t('placeholder_code') }),
  });

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: {
      step: userForm.step,
      username: userForm.username,
      password: '',
      code: '',
    },
  });

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const result = await form.trigger();
    if (!result) return;
    const values = form.getValues();
    startTransition(() => action(values));
  };

  return (
    <Card className="w-full max-w-[400px]">
      <CardContent className="p-6">
        <Form {...form}>
          <form onSubmit={handleSubmit} className="block w-full space-y-5">
            {state?.error && (
              <Alert variant="destructive">
                <AlertIcon>
                  <AlertCircle />
                </AlertIcon>
                <div>
                  <AlertError errors={state?.error} />
                </div>
              </Alert>
            )}

            <div className="text-center space-y-1 pb-3">
              <h1 className="text-xl font-semibold tracking-tight">
                {t('forget_password')}
              </h1>
              <p className="text-xs text-muted-foreground">
                {t('enter_new_password')}
              </p>
            </div>

            <FormField
              control={form.control}
              name="password"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>{t('new_password')}</FormLabel>
                  <FormControl>
                    <Input
                      type="text"
                      placeholder={t('placeholder_password')}
                      {...field}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            <div className="flex flex-col">
              <FormField
                control={form.control}
                name="code"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel className="!flex-row !justify-center">
                      {t('confirm_code')}
                    </FormLabel>
                    <FormControl>
                      <Input
                        type="text"
                        placeholder={t('sended_code')}
                        {...field}
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <div className="flex items-center gap-x-2 my-2 font-medium">
                <RecentCodeResetPassword id={userForm.userId} />
              </div>
            </div>

            <Button type="submit" className="w-full" disabled={pending}>
              {pending ? <Spinner className="size-4 animate-spin" /> : null}
              {t('add')}
            </Button>

            <div className="space-y-3">
              <Button
                type="button"
                onClick={() => setUserForm({ ...userForm, step: 2 })}
                variant="outline"
                className="w-full"
              >
                <ArrowLeft className="size-3.5" /> {t('back')}
              </Button>
            </div>
          </form>
        </Form>
      </CardContent>
    </Card>
  );
};
export default Step3;
