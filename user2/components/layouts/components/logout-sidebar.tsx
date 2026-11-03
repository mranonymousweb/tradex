import { useActionState } from 'react';
import { useTranslations } from 'next-intl';
import { logout } from '@/services/auth.services';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinners';

export default function LogoutSidebar() {
    const t = useTranslations('sidebar');
    const [state, action, pending] = useActionState(logout, null);
    return (
        <div className="logout-sidebar w-full px-4 pb-0">
            <form action={action}>
                <Button
                    data-slot="sidebar-logout"
                    variant="outline"
                    className="w-full flex items-center justify-center"
                    aria-disabled={pending}
                >
                    {pending ? (
                        <Spinner className="size-4 animate-spin" />
                    ) : (
                        <i className="ki-outline ki-exit-left text-lg"></i>
                    )}
                    <span data-slot="sidebar-logout-title" className="ml-2">
                        {t('logout_account')}
                    </span>
                </Button>
            </form>
        </div>
    );
}
