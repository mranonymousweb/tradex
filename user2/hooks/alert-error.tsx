import { AlertCircle } from 'lucide-react';
import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';

export const AlertError = ({ errors }: { errors: any }) => {
  if (!errors) return;

  return (
    <Alert variant="destructive" appearance="light" className="flex flex-col">
      {errors.map(([_, value]: [string, string], i: number) => (
        <div key={i} className="flex items-center gap-x-2">
          <AlertIcon>
            <AlertCircle color="oklch(57.7% 0.245 27.325)" />
          </AlertIcon>
          <AlertTitle key={i}> {`${value}`} </AlertTitle>
        </div>
      ))}
    </Alert>
  );
};
