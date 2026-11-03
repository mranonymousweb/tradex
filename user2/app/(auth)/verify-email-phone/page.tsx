import { Suspense } from 'react';
import { redirect } from 'next/navigation';
import { getuser } from '@/lib/user';
import { Content } from './content';

export default async function Page() {
    const data = await getuser();
    if (!data?.user) {
        redirect('/signin');
    }

    return (
        <Suspense>
            <Content user={data?.user} />
        </Suspense>
    );
}
