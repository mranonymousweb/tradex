'use client';

import { Spinner } from '../ui/spinners';

export function ContentLoader() {
  return (
    <div className="flex flex-col items-center justify-center self-center relative top-1/2 -translate-x-1/2">
      <div className="flex items-center gap-2.5">
        <Spinner className="animate-spin text-muted-foreground opacity-50" />
        <span className="text-muted-foreground font-medium text-sm">
          Loading...
        </span>
      </div>
    </div>
  );
}
