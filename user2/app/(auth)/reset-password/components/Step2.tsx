import {
  Dispatch,
  SetStateAction,
  startTransition,
  useActionState,
  useEffect,
} from 'react';
import { hideText } from '@/helper/hideText';
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
import { Spinner } from '@/components/ui/spinners';

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

const Step2: React.FC<Props> = ({ userForm, setUserForm }) => {
  const t = useTranslations('auth');
  const [state, action, pending] = useActionState(forgetPassword, null);

  useEffect(() => {
    if (state?.success) {
      setUserForm({
        ...userForm,
        step: 3,
      });
    }
  }, [state, setUserForm, userForm]);

  const formSchema = z.object({
    username: z.string(),
    step: z.number(),
    type: z.string(),
  });

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: {
      step: userForm.step,
      username: userForm.username,
      type: 'sms',
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
                {t('type_send_code')}
              </p>
            </div>

            <FormField
              control={form.control}
              name="type"
              render={({ field }) => (
                <FormItem className="!flex-row">
                  <FormControl>
                    <input
                      type="radio"
                      name="type"
                      defaultChecked={field.value === 'sms'}
                      onChange={() => field.onChange('sms')}
                    />
                  </FormControl>
                  <FormLabel>{hideText(userForm?.mobile)}</FormLabel>
                  <FormMessage />
                </FormItem>
              )}
            />

            <FormField
              control={form.control}
              name="type"
              render={({ field }) => (
                <FormItem className="!flex-row">
                  <FormControl>
                    <input
                      type="radio"
                      name="type"
                      onChange={() => field.onChange('email')}
                    />
                  </FormControl>
                  <FormLabel dir="ltr">{hideText(userForm?.email)}</FormLabel>
                  <FormMessage />
                </FormItem>
              )}
            />

            <Button type="submit" className="w-full" disabled={pending}>
              {pending ? <Spinner className="size-4 animate-spin" /> : null}
              {t('next')}
            </Button>

            <div className="space-y-3">
              <Button
                type="button"
                onClick={() => setUserForm({ ...userForm, step: 1 })}
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
export default Step2;
