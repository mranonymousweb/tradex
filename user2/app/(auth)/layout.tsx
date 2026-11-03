import { ReactNode } from 'react';
import Link from 'next/link';
import { toAbsoluteUrl } from '@/lib/helpers';
import { AuthProvider } from '@/providers/auth-provider';
import { metaData } from '@/services/metaData.services';
import AuthLayout from '@/components/layouts/AuthLayout';

export const dynamic = 'force-dynamic';

export default async function Layout({ children }: { children: ReactNode }) {
  const data = await metaData();

  return (
    <>
      <style>
        {`
                .page-bg {
                    background-image: url('${toAbsoluteUrl('/media/images/2600x1200/bg-10.png')}');
                }
                .dark .page-bg {
                    background-image: url('${toAbsoluteUrl('/media/images/2600x1200/bg-10-dark.png')}');
                }
                `}
      </style>
      <div className="flex flex-col items-center justify-center grow bg-center bg-no-repeat page-bg">
        <div className="m-5">
          <Link href="/">
            {data &&
              data?.meta?.meta_data_logo &&
              data?.meta?.meta_data_dark_logo && (
                <>
                  <img
                    src={data?.meta?.meta_data_logo ?? ''}
                    className="h-[45px] max-w-none dark:hidden"
                    alt="logo"
                  />
                  <img
                    src={data?.meta?.meta_data_dark_logo ?? ''}
                    className="h-[45px] max-w-none hidden dark:block"
                    alt="logo dark"
                  />
                </>
              )}
          </Link>
        </div>
        <AuthProvider>
          <AuthLayout>{children}</AuthLayout>
        </AuthProvider>
      </div>
    </>
  );
}
