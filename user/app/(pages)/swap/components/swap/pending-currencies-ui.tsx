import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { DataLoading } from '@/components/ui/data-loading';

export const PendingCurrency = () => {
  return (
    <Card className="grow animate-pulse ">
      <CardHeader>
        <CardTitle>
          <div className="h-2 w-32 rounded bg-gray-200"></div>
        </CardTitle>
      </CardHeader>
      <CardContent className="min-h-96 flex items-center justify-center">
        <DataLoading />
      </CardContent>
    </Card>
  );
};
