import { startTransition, useActionState, useEffect, useState } from 'react';
import Link from 'next/link';
import { decimalPipe } from '@/helper/decimalPipe';
import { zodResolver } from '@hookform/resolvers/zod';
import { useQueryClient } from '@tanstack/react-query';
import { User } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import { toAbsoluteUrl } from '@/lib/helpers';
import { createLink } from '@/services/marketing.services';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import DialogContent, {
  Dialog,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinners';
import { HexagonBadge } from '@/components/partials/common/hexagon-badge';
import { FormCreate, FormCreateSchemaType } from './create-link-schema';

export const CodeMarketing = ({ income }: { income?: number }) => {
  const t = useTranslations('marketing');
  const [open, setOpen] = useState(false);
  const [state, action, pending] = useActionState(createLink, null);
  const queryClient = useQueryClient();

  const form = useForm<FormCreateSchemaType>({
    resolver: zodResolver(FormCreate()),
    defaultValues: {
      name: '',
      user_percentage: '',
    },
  });

  useEffect(() => {
    if (state?.success) {
      queryClient.invalidateQueries({ queryKey: ['links-marketing'] });
      toast.success(state.message);
      setOpen(false);
    }
  }, [state, queryClient]);

  const handleFormSubmit = () => {
    const values = form.getValues();
    startTransition(() => action(values));
  };

  return (
    <>
      <style>
        {`
                .user-access-bg {
                background-image: url('${toAbsoluteUrl('/media/images/2600x1200/bg-5.png')}');
                }
                .dark .user-access-bg {
                background-image: url('${toAbsoluteUrl('/media/images/2600x1200/bg-5-dark.png')}');
                }
            `}
      </style>
      <Card>
        <CardHeader className="mb-5" id="external_services_manage_api">
          <CardTitle>{t('refral_code')}</CardTitle>
        </CardHeader>
        <CardContent className="lg:py-7.5 grid gap-5 lg:gap-7.5">
          <div
            className={`flex items-center flex-wrap sm:flex-nowrap justify-between grow border border-border rounded-xl gap-2 p-5 
                        rtl:[background-position:-195px_-85px] [background-position:195px_-85px] bg-no-repeat bg-[length:650px] user-access-bg`}
          >
            <div className="flex items-center gap-4">
              <HexagonBadge
                stroke="stroke-orange-200 dark:stroke-orange-950"
                fill="fill-orange-50 dark:fill-orange-950/30"
                size="size-[50px]"
                badge={<User size={20} className="text-xl text-orange-400" />}
              />
              <div className="flex flex-col gap-1.5">
                <div className="flex items-center flex-wrap gap-2.5">
                  <Link
                    href="#"
                    className="text-base font-medium text-mono hover:text-primary-active"
                  >
                    {t('income_you')}
                  </Link>
                  <Badge size="sm" variant="secondary" appearance="outline">
                    {income ? decimalPipe(income) : 0} {t('tether_equivalent')}
                  </Badge>
                </div>
                <div className="form-info text-foreground font-normal text-xs">
                  {t('income_description_up')}
                  <br />
                  {t('income_description_down')}
                </div>
              </div>
            </div>
            <div className="flex items-center flex-wrap md:flex-nowrap gap-1.5">
              <Button variant="primary" onClick={() => setOpen(true)}>
                {t('create_link')}
              </Button>
              <Dialog open={open} onOpenChange={() => setOpen(false)}>
                <DialogContent>
                  <DialogHeader>
                    <DialogTitle>{t('create_link')}</DialogTitle>
                  </DialogHeader>
                  <Form {...form}>
                    <form onSubmit={form.handleSubmit(handleFormSubmit)}>
                      <FormField
                        control={form.control}
                        name="name"
                        render={({ field }) => (
                          <FormItem>
                            <Label className="mb-0">{t('link_name')}</Label>
                            <FormControl>
                              <Input
                                placeholder={t('placeholder_name_link')}
                                {...field}
                              />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      <FormField
                        control={form.control}
                        name="user_percentage"
                        render={({ field }) => (
                          <FormItem className="mt-2">
                            <Label className="mb-0">
                              {t('percentage_marketing')}
                            </Label>
                            <FormControl>
                              <Input
                                type="number"
                                placeholder={t('percentage_subset')}
                                {...field}
                              />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      <DialogFooter>
                        <Button
                          type="button"
                          variant="outline"
                          onClick={() => setOpen(false)}
                        >
                          {t('cancel')}
                        </Button>
                        <Button type="submit" disabled={pending}>
                          {pending ? (
                            <Spinner className="size-4 animate-spin" />
                          ) : null}
                          {t('create_link')}
                        </Button>
                      </DialogFooter>
                    </form>
                  </Form>
                </DialogContent>
              </Dialog>
            </div>
          </div>
          <p className="text-sm text-muted-foreground">
            {t('refral_description')}
          </p>
        </CardContent>
      </Card>
    </>
  );
};
