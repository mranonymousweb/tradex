import { useMutation, useQueryClient } from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { sendMessage } from '@/services/ticket.services';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';

interface Props {
  ticketId?: string;
  message: string;
  files: File[];
  clearForm: () => void;
}

export const SendMessageAction: React.FC<Props> = ({
  ticketId,
  message,
  files,
  clearForm,
}) => {
  const t = useTranslations('ticket');
  const queryClient = useQueryClient();

  const mutation = useMutation({
    mutationFn: (formData: FormData) => sendMessage(formData),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['chat', ticketId] });
      queryClient.invalidateQueries({ queryKey: ['tickets'] });
    },
    onError: (error) => {
      toast.error(error.message);
    },

    onSettled: () => {
      clearForm();
    },
  });

  const handleSubmit = () => {
    if (!ticketId) return;
    const data = new FormData();
    data.append('ticketId', ticketId);
    data.append('message', message);
    files.forEach((file) => data.append('files[]', file));
    mutation.mutate(data);
  };

  return (
    <Button
      type="button"
      size="sm"
      variant="mono"
      onClick={handleSubmit}
      disabled={mutation.isPending || (!message && !files.length)}
    >
      {mutation.isPending ? <Spinner className="animate-spin" /> : null}
      {t('filter.send')}
    </Button>
  );
};
