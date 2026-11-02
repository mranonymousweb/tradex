import { useActionState } from 'react';
import { useTranslations } from 'next-intl';
import { logout } from '@/services/auth.services';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';

export const Logout = () => {
  const t = useTranslations('header.user');
  const [state, action, pending] = useActionState(logout, null);

  return (
    <form action={action}>
      <Button variant="outline" type="submit" size="md" className="w-full">
        {pending && <Spinner className="animate-spin" />}
        {t('logout')}
      </Button>
      {state?.error && (
        <span className="text-destructive text-xs text-center flex">
          {t('error')}
        </span>
      )}
    </form>
  );
};
