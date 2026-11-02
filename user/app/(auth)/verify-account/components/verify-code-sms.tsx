import { useState } from 'react';
import { hideText } from '@/helper/hideText';
import { useTranslations } from 'next-intl';
import { User } from '@/types/auth';
import { Input } from '@/components/ui/input';
import { RecentCode } from './RecentCode';

interface Props {
    input: string;
    setInput: (input: string) => void;
    user: User;
}

export const VerifyAccountCode = ({ input, setInput, user }: Props) => {
    const t = useTranslations('auth');
    const [isFocus, setIsFocus] = useState(false);
    const chars = [...Array(6)].map((_, i) => input.charAt(5 - i));
    const username = user?.iranian
        ? user?.confirm_mobile_with_email === "active"
            ? hideText(user?.email)
            : hideText(user?.mobile)
        : hideText(user?.email);

    return (
        <>
            <div className="text-center mb-2">
                <h3 className="text-lg font-semibold text-foreground mb-5">
                    {t('confirm_account')}
                </h3>
                <div className="flex flex-col">
                    <span className="text-2sm text-muted-foreground font-medium mb-1.5">
                        {t('sended_code')}
                    </span>
                    <span
                        dir="ltr"
                        className="text-sm font-semibold text-gray-900 dark:text-gray-400"
                    >
                        {username}
                    </span>
                </div>
            </div>
            <div className="flex flex-wrap justify-center gap-2.5 relative" dir="rtl">
                {chars.map((char, index) => (
                    <Input
                        key={index}
                        type="text"
                        defaultValue={char}
                        className={`${isFocus && 'ring ring-muted-foreground'} ${char && isFocus && 'border-primary-clarity ring-primary'} size-10 shrink-0 px-0 text-center`}
                        maxLength={1}
                        readOnly
                    />
                ))}
                <input
                    type="text"
                    name="code"
                    value={input}
                    maxLength={6}
                    onFocus={() => setIsFocus(true)}
                    onBlur={() => setIsFocus(false)}
                    onChange={(e) => setInput(e.target.value)}
                    className="w-11/12 h-full absolute opacity-0"
                />
            </div>
            <div className="flex items-center gap-x-2 justify-center mb-2 font-medium">
                <RecentCode />
            </div>
        </>
    );
};
