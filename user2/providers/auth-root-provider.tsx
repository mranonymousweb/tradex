import { headers } from 'next/headers';
import { redirect } from 'next/navigation';
import { isGuestAccessiblePath } from '@/lib/guest-routes';
import { getSession } from '@/lib/session';

interface AuthProviderProps {
  children: React.ReactNode;
}

export async function AuthRootProvider({ children }: AuthProviderProps) {
  const pathname = (await headers()).get('x-pathname') ?? '';

  if (isGuestAccessiblePath(pathname)) {
    return children;
  }

  const session = await getSession();
  if (!session?.user?.token) {
    redirect('/signin');
  }

  return children;
}
