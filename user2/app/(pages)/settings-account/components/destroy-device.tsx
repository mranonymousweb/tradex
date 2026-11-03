import { useActionState, useEffect } from 'react';
import { LogOut } from 'lucide-react';
import { toast } from 'sonner';
import { destroyDevices } from '@/services/setting-account.services';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';

export const DestroyDevice = ({ id }: { id: number }) => {
  const [state, action, pending] = useActionState(destroyDevices, null);

  useEffect(() => {
    if (state?.success) {
      toast.success(state.message);
      window.location.reload();
    }
  }, [state]);

  return (
    <form action={action}>
      <input name="id" type="hidden" defaultValue={id} />
      <Button type="submit" variant="ghost" mode="icon" disabled={pending}>
        {pending ? (
          <Spinner className="size-4 animate-spin" />
        ) : (
          <LogOut size={16} className="rtl:-scale-100" />
        )}
      </Button>
    </form>
  );
};
