'use client';

import { useState } from 'react';
import { useTheme } from 'next-themes';
import { Button } from '@/components/ui/button';

export function ThemeToggle() {
  const { theme, setTheme } = useTheme();
  const [isDark, setIsDark] = useState(theme === 'dark');

  const handleThemeToggle = (theme: boolean) => {
    setTheme(theme ? 'dark' : 'light');
    setIsDark(theme);
  };

  return (
    <Button
      variant="ghost"
      shape="circle"
      mode="icon"
      onClick={() => handleThemeToggle(!isDark)}
      className="size-9 hover:bg-primary/10 hover:[&_svg]:text-primary pt-2"
    >
      <div className="relative">
        <i
          className={`ki-outline text-2xl md:text-lg ${isDark ? 'ki-sun' : 'ki-moon'}`}
        ></i>
      </div>
    </Button>
  );
}
