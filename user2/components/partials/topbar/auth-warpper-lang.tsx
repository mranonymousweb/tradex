import { useState } from 'react';
import { useLocale } from 'next-intl';
import { I18N_LANGUAGES } from '@/config/menu.config';
import { LangDropdownMenu } from './lang-dropdown-menu';

export const WrapperLangDropdownAuth = () => {
  const defaultLocale = useLocale();
  const [select, setSelect] = useState(defaultLocale);
  const selectedLang = I18N_LANGUAGES.find((lang) => lang.code === select);

  return (
    <LangDropdownMenu
      setSelect={setSelect}
      trigger={
        <div
          dir="rtl"
          className="flex border items-center justify-between w-24 px-2 py-1.5 rounded-md"
        >
          <img
            src={selectedLang?.flag}
            className="w-4 h-4 rounded-full"
            alt={selectedLang?.name}
          />
          <span className="text-xs font-semibold text-secondary-foreground font-sans">
            {selectedLang?.name}
          </span>
        </div>
      }
    />
  );
};
