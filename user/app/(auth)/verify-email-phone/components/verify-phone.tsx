'use client';

import { useState } from 'react';
import { hideText } from '@/helper/hideText';
import { useTranslations } from 'next-intl';
import { User } from '@/types/auth';
import { Input } from '@/components/ui/input';
import { Smartphone } from 'lucide-react';

interface Props {
    phoneCode: string;
    setPhoneCode: (phoneCode: string) => void;
    user: User;
}

export const VerifyPhone = ({ phoneCode, setPhoneCode, user }: Props) => {
    const t = useTranslations('auth');
    const [isFocus, setIsFocus] = useState(false);
    const chars = [...Array(6)].map((_, i) => phoneCode.charAt(5 - i));
    const mobile = hideText(user?.mobile);

    return (
        <div className="flex flex-col gap-4 rounded-xl border border-border p-2 md:p-4">
            <div className="flex items-center gap-2">
                <Smartphone className="size-6 text-primary" />
                <h4 className="text-sm font-semibold text-foreground">
                    {t('confirm_mobile')}
                </h4>
            </div>

            <div className="flex flex-col text-center">
                <span className="text-2sm text-muted-foreground font-medium mb-1.5">
                    {t('sended_code')}
                </span>
                <span
                    dir="ltr"
                    className="text-sm font-semibold text-gray-900 dark:text-gray-400"
                >
                    {mobile}
                </span>
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
                    name="phoneCode"
                    inputMode="numeric"
                    autoComplete="one-time-code"
                    value={phoneCode}
                    maxLength={6}
                    onFocus={() => setIsFocus(true)}
                    onBlur={() => setIsFocus(false)}
                    onChange={(e) =>
                        setPhoneCode(e.target.value.replace(/\D/g, ''))
                    }
                    className="w-11/12 h-full absolute opacity-0"
                />
            </div>
        </div>
    );
};
