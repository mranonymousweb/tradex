import { useState } from 'react';
import { Check } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { FormType } from './constants';

export const Tabs = ({
    tab,
    setTab,
}: {
    tab: FormType;
    setTab: (value: FormType) => void;
}) => {
    const t = useTranslations('spot');
    const [dropDown, setDropDown] = useState(FormType.StopLimit);

    return (
        <div className="flex items-center gap-x-2 text-xs font-medium text-muted-foreground border-b w-full">
            <div
                className={`pb-2 cursor-pointer hover:text-mono ${tab === FormType.Limit && 'active_tab'}`}
                onClick={() => setTab(FormType.Limit)}
            >
                <span>{t('limit')}</span>
            </div>
            <div
                className={`pb-2 cursor-pointer hover:text-mono ${tab === FormType.Market && 'active_tab'}`}
                onClick={() => setTab(FormType.Market)}
            >
                <span>{t('market_tab')}</span>
            </div>
            <div className="tab-drop relative cursor-pointer">
                <div
                    className={`
                        tab-drop--selected flex items-center pb-2 space-x-2 
                        ${(tab === FormType.StopLimit || tab === FormType.StopMarket) && 'active_tab'}`}
                    onClick={() => setTab(dropDown)}
                >
                    <span>
                        {`${
                            dropDown === FormType.StopLimit
                                ? t('stop_limit')
                                : dropDown === FormType.StopMarket
                                  ? t('stop_market')
                                  : 'OCO'
                        }`}
                    </span>
                    <svg
                        fill="IconNormal"
                        className="w-4 h-4"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M16.5 8.49v2.25L12 15.51l-4.5-4.77V8.49h9z"
                            fill="currentColor"
                        ></path>
                    </svg>
                </div>

                {/*drop down*/}
                <div
                    className={`
                    tab-drop--list flex flex-col left-0 top-6 z-[1] 
                    absolute bg-background border shadow-lg rounded-lg w-32`}
                >
                    <div
                        className={`
                            flex justify-between items-center p-2 hover:bg-secondary 
                            cursor-pointer ${dropDown === FormType.StopLimit && 'bg-secondary'}`}
                        onClick={() => (
                            setDropDown(FormType.StopLimit),
                            setTab(FormType.StopLimit)
                        )}
                    >
                        <span className="text-secondary-foreground">
                            {t('stop_limit')}
                        </span>
                        <Check
                            size={15}
                            className={`mb-1 ${dropDown !== FormType.StopLimit && 'hidden'}`}
                        />
                    </div>

                    <div
                        className={`
                            flex justify-between items-center p-2 hover:bg-secondary 
                            cursor-pointer ${dropDown === FormType.StopMarket && 'bg-secondary'}`}
                        onClick={() => (
                            setDropDown(FormType.StopMarket),
                            setTab(FormType.StopMarket)
                        )}
                    >
                        <span className="text-secondary-foreground">
                            {t('stop_market')}
                        </span>
                        <Check
                            size={15}
                            className={`mb-1 ${dropDown !== FormType.StopMarket && 'hidden'}`}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};
