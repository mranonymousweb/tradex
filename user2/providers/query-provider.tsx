'use client';

import { ReactNode, useState } from 'react';
//import { RiErrorWarningFill } from '@remixicon/react';
import {
    QueryCache,
    QueryClient,
    QueryClientProvider,
} from '@tanstack/react-query';
import { useTranslations } from 'next-intl';
import { AUTH_LOGOUT_PATH } from '@/lib/auth-session';
import { ApiError } from '@/lib/api-error';

//import { toast } from 'sonner';
//import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';

const QueryProvider = ({ children }: { children: ReactNode }) => {
    const t = useTranslations('errors');

    const [queryClient] = useState(
        () =>
            new QueryClient({
                defaultOptions: {
                    queries: {
                        staleTime: 30_000,
                    },
                },
                queryCache: new QueryCache({
                    onError: (error) => {
                        if (error instanceof ApiError && error.type === 'UNAUTHORIZED') {
                            window.location.assign(AUTH_LOGOUT_PATH);
                        }
                        //console.log("message", error)
                        //const message = t("unknown");
                        //toast.custom(
                        //  () => (
                        //    <Alert variant="destructive" icon="destructive" close={false}>
                        //      <AlertIcon>
                        //        <RiErrorWarningFill />
                        //      </AlertIcon>
                        //      <AlertTitle>{message}</AlertTitle>
                        //    </Alert>
                        //  ),
                        //  {
                        //    position: 'top-center',
                        //  },
                        //);
                    },
                }),
            }),
    );

    return (
        <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
    );
};

export { QueryProvider };
