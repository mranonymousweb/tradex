import Link from 'next/link';
import { Download } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { TicketFile } from '@/types/Ticket';
import { Button } from '@/components/ui/button';

interface Props {
  file: TicketFile;
}

export const DownLoadFile: React.FC<Props> = ({ file }) => {
  const t = useTranslations('ticket');

  return (
    <Link href={file?.path} target="_blank">
      <Button asChild className="p-1" variant="outline">
        <span className="flex items-center gap-1">
          <Download size={20} />
          <span className="text-xs">{t('attach_file')}</span>
        </span>
      </Button>
    </Link>
  );
};
