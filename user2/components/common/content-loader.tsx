import { cn } from '@/lib/utils';
import { Spinner } from '../ui/spinners';

export function ContentLoader({ className }: { className?: string }) {
  return (
    <div
      className={cn('flex items-center justify-center grow w-full', className)}
    >
      <div className="flex items-center gap-2.5 border rounded-md p-2">
        <Spinner className="animate-spin text-muted-foreground" />
        <span className="font-medium text-xs">درحال بارگذاری ...</span>
      </div>
    </div>
  );
}
