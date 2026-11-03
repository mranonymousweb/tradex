'use client';

import { useEffect } from 'react';
import Link from 'next/link';
import { useRouter, useSearchParams } from 'next/navigation';
import { decimalPipe } from '@/helper/decimalPipe';
import { CircleCheckBig, CircleX } from 'lucide-react';
import { useTranslations } from 'next-intl';

export default function Page() {
  const t = useTranslations('public');
  const searchParams = useSearchParams();
  const router = useRouter();
  const amount = Number(searchParams.get('amount'));
  const transactionId = searchParams.get('transaction_id');
  const txtId = searchParams.get('txt_id');
  const status = searchParams.get('status');

  useEffect(() => {
    if (searchParams.size === 0) {
      return router.push('/');
    }
    setTimeout(() => {
      router.push('/');
    }, 10000);
  }, [searchParams.size, router]);

  return (
    <div className="flex flex-col items-center justify-center h-[95%] w-full">
      <div className="border p-10 flex flex-col items-center w-full md:w-96 rounded-lg shadow-2xs">
        <div className="mb-5">
          {status === '200' ? (
            <CircleCheckBig size={45} color="#2EBD85" />
          ) : (
            <CircleX size={45} color="#e7000b" />
          )}
        </div>
        {status === '200' ? (
          <h3 className="text-2xl font-semibold text-green text-center mb-2">
            {t('receipt.success_text')}
          </h3>
        ) : (
          <h3 className="text-2xl font-semibold text-destructive text-center mb-2">
            {t('receipt.reject_text')}
          </h3>
        )}
        <p className="text-center text-secondary-foreground text-sm mb-10">
          {t('receipt.alert_description')}
        </p>
        <ul className="bg-secondary/50 w-full p-3 text-sm flex flex-col gap-y-2 rounded-md">
          <li className="flex flex-wrap items-center justify-between">
            <span className="text-secondary-foreground">
              {t('receipt.amount')} ({t('receipt.toman')})
            </span>
            <span>{decimalPipe(+amount, 0) ?? '--'}</span>
          </li>
          <li className="flex flex-wrap items-center justify-between">
            <span className="text-secondary-foreground">
              {t('receipt.id_transaction')}
            </span>
            <span>{transactionId ?? '--'}</span>
          </li>
          <li className="flex flex-wrap items-center justify-between">
            <span className="text-secondary-foreground">
              {t('receipt.trackig_code')}
            </span>
            <span>{txtId ?? '--'}</span>
          </li>
        </ul>
        <Link
          className={`w-full text-background text-center py-1.5 mt-5 rounded-lg text-sm font-medium ${status === '200' ? 'bg-green' : 'bg-destructive'}`}
          href="/"
        >
          {t('receipt.back_site')}
        </Link>
      </div>
    </div>
  );
}
