import { startTransition, useActionState, useEffect, useState } from 'react';
import { zodResolver } from '@hookform/resolvers/zod';
import { useQueryClient } from '@tanstack/react-query';
import { SquarePen } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { useForm } from 'react-hook-form';
import { toast } from 'sonner';
import { createLink } from '@/services/marketing.services';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
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
import { FormCreate, FormCreateSchemaType } from './create-link-schema';

interface Item {
  id: number;
  name: string;
  user_percentage: string;
  is_active: boolean;
}

export const UpdateLink = ({ item }: { item: Item }) => {
  const t = useTranslations('marketing');
  const [open, setOpen] = useState(false);
  const [state, action, pending] = useActionState(createLink, null);
  const queryClient = useQueryClient();
  const closeDialog = () => setOpen(false);

  useEffect(() => {
    if (state?.success) {
      toast.success(t('success_full'));
      queryClient.invalidateQueries({ queryKey: ['links-marketing'] });
      setOpen(false);
    }
  }, [state, queryClient]);

  const form = useForm<FormCreateSchemaType>({
    resolver: zodResolver(FormCreate()),
    defaultValues: {
      id: item.id,
      name: item.name,
      user_percentage: item.user_percentage,
      is_active: item.is_active,
    },
  });

  const handleFormSubmit = () => {
    const values = form.getValues();
    startTransition(() => action(values));
  };

  return (
    <>
      <Button
        type="button"
        size="sm"
        variant="secondary"
        onClick={() => setOpen(true)}
      >
        <SquarePen size={20} />
      </Button>

      <Dialog open={open} onOpenChange={closeDialog}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>{t('update_link')}</DialogTitle>
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
                    <Label className="mb-0">{t('percentage_marketing')}</Label>
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
              <FormField
                control={form.control}
                name="is_active"
                render={({ field }) => (
                  <FormItem className="mt-4 flex-row items-center">
                    <FormControl>
                      <Checkbox
                        onCheckedChange={field.onChange}
                        checked={field.value ?? true}
                      />
                    </FormControl>
                    <Label className="mb-0">
                      {field.value ? t('active') : t('not_active')}
                    </Label>
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
                  {pending ? <Spinner className="size-4 animate-spin" /> : null}
                  {t('confirm')}
                </Button>
              </DialogFooter>
            </form>
          </Form>
        </DialogContent>
      </Dialog>
    </>
  );
};
