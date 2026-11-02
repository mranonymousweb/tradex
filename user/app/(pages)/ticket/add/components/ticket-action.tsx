import {
  Dispatch,
  Fragment,
  SetStateAction,
  startTransition,
  useActionState,
  useEffect,
} from 'react';
import { useRouter } from 'next/navigation';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { AlertError } from '@/hooks/alert-error';
import { addTicket } from '@/services/ticket.services';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';

interface Props {
  setErrors: Dispatch<SetStateAction<string[]>>;
  title: string;
  description: string;
  departmentId: string;
  files: File[];
}

export const TicketActions: React.FC<Props> = ({
  setErrors,
  title,
  description,
  departmentId,
  files,
}) => {
  const t = useTranslations('ticket');
  const router = useRouter();
  const [state, action, pending] = useActionState(addTicket, null);

  useEffect(() => {
    if (state?.success) {
      toast.success(state.message);
      router.push('/ticket/list');
    }
  }, [state, router, setErrors]);

  const handleSubmit = () => {
    if (!title) {
      setErrors([t('validation.choose_title_ticket')]);
      return;
    }
    if (!departmentId) {
      setErrors([t('validation.choose_department_ticket')]);
      return;
    }
    if (!description) {
      setErrors([t('validation.choose_description_ticket')]);
      return;
    }
    if (files && files.length > 0) {
      const totalSize = files.reduce((acc, file) => acc + file.size, 0);
      const maxSize = 100 * 1024 * 1024;
      if (totalSize > maxSize) {
        setErrors([t('validation.validate_files')]);
        return;
      }
    }

    const data = new FormData();
    data.append('title', title);
    data.append('description', description);
    data.append('departmentId', departmentId);
    data.append('priority', '2');
    files.forEach((file) => data.append('files[]', file));
    startTransition(() => action(data));
  };

  return (
    <Fragment>
      <Button type="button" onClick={handleSubmit}>
        {pending ? <Spinner className="size-4 animate-spin" /> : null}
        {t('register_ticket')}
      </Button>
      <div className="flex justify-start w-full mt-4">
        {state?.error && <AlertError errors={state.error} />}
      </div>
    </Fragment>
  );
};
