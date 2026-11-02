import NextTopLoader from 'nextjs-toploader';
import { BaseLayout } from '@/components/layouts/BaseLayout';
import { AuthRootProvider } from '@/providers/auth-root-provider';

// export const dynamic = 'force-dynamic';

export default async function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <AuthRootProvider>
    <BaseLayout>
      <NextTopLoader
        color="var(--color-primary)"
        initialPosition={0.08}
        crawlSpeed={200}
        height={4}
        crawl={true}
        showSpinner={false}
      />
      {children}
    </BaseLayout>
    </AuthRootProvider>
  );
}
