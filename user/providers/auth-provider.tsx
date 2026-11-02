import { redirect } from 'next/navigation';
import { getSession } from '@/lib/session';

interface AuthProviderProps {
  children: React.ReactNode;
}

export async function AuthProvider({ children }: AuthProviderProps) {
  const session = await getSession();
  if (session?.user?.token) {
    redirect('/');
  }
  return children;
}
