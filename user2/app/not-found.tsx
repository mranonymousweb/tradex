import Link from 'next/link';
import { getTranslations } from 'next-intl/server';

export const dynamic = 'force-dynamic';

export default async function NotFound() {
    const t = await getTranslations('notFound');

    return (
        <div className="flex flex-col items-center justify-center h-[95%] w-full">
            <div className="mb-10">
                <img
                    alt=""
                    className="dark:hidden max-h-[160px]"
                    src="/media/illustrations/19.svg"
                />
                <img
                    alt=""
                    className="light:hidden max-h-[160px]"
                    src="/media/illustrations/19-dark.svg"
                />
            </div>
            <span className="kt-badge kt-badge-primary kt-badge-outline mb-3">
                {t('badge')}
            </span>
            <h3 className="text-2xl font-semibold text-mono text-center mb-2">
                {t('title')}
            </h3>
            <div className="text-base text-center text-secondary-foreground mb-10">
                <Link className="text-primary font-medium hover:text-primary" href="/">
                    {t('back')}
                </Link>
            </div>
        </div>
    );
}
