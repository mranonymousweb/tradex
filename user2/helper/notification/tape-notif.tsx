import { useState } from 'react';
import Link from 'next/link';
import { AlertCircle } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { Notification } from '@/types/notification';
import {
  Alert,
  AlertDescription,
  AlertIcon,
  AlertTitle,
} from '@/components/ui/alert';

export const TapeNotif = ({ tape }: { tape: Notification }) => {
  const t = useTranslations('notification');
  const [show, setShow] = useState(tape.status === 'active');

  return (
    <Alert
      variant="destructive"
      appearance="light"
      hidden={!show}
      close
      onClose={() => setShow(false)}
      className={`flex !rounded-none'}`}
      style={{ borderRadius: '0px', marginBottom: '7px' }}
    >
      <AlertIcon>
        <AlertCircle color="oklch(57.7% 0.245 27.325)" />
      </AlertIcon>
      <AlertTitle className="flex gap-1">
        <AlertDescription>{tape.title}</AlertDescription>
        {tape.description !== '#' && (
          <Link
            href={tape.description}
            className="text-primary underline"
            target="_blank"
          >
            {t('show_more')}
          </Link>
        )}
      </AlertTitle>
    </Alert>
  );
};
