import { ReactNode, startTransition } from 'react';
import { Locale } from '@/i18n/config';
import { useLocale } from 'next-intl';
import { I18N_LANGUAGES } from '@/config/menu.config';
import { setUserLocale } from '@/lib/locale';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuRadioGroup,
  DropdownMenuRadioItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

export function LangDropdownMenu({
  trigger,
  setSelect,
}: {
  trigger: ReactNode;
  setSelect?: (s: string) => void;
}) {
  const defaultLocale = useLocale();

  const handleLanguage = (value: string) => {
    const locale = value as Locale;
    startTransition(() => {
      setUserLocale(locale);
    });

    if (setSelect) {
      setSelect(locale);
    }

    setTimeout(() => {
      window.location.reload();
    }, 2000);
  };

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>{trigger}</DropdownMenuTrigger>
      <DropdownMenuContent className="w-48">
        <DropdownMenuItem className="!bg-inherit">
          <DropdownMenuRadioGroup
            className="w-full"
            value={defaultLocale}
            onValueChange={(value) => {
              const selectedLang = I18N_LANGUAGES.find(
                (lang) => lang.code === value,
              );
              if (selectedLang) handleLanguage(selectedLang.code);
            }}
          >
            {I18N_LANGUAGES.map((item) => (
              <DropdownMenuRadioItem
                key={item.code}
                value={item.code}
                className="flex items-center gap-2 w-full hover:bg-muted"
              >
                <img
                  src={item.flag}
                  className="w-4 h-4 rounded-full"
                  alt={item.name}
                />
                <span className="text-secondary-foreground text-sm">
                  {item.name}
                </span>
              </DropdownMenuRadioItem>
            ))}
          </DropdownMenuRadioGroup>
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}
