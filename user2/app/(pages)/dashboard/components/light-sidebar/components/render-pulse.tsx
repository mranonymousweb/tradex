import { Card, CardContent } from '@/components/ui/card';

export const RenderItemPulse = () => {
  const array = Array.from({ length: 4 });
  return array.map((__, i) => (
    <Card key={i}>
      <CardContent className="animate-pulse p-5 flex flex-col justify-between gap-6 h-full bg-cover rtl:bg-[left_top_-1.7rem] bg-[right_top_-1.7rem] bg-no-repeat channel-stats-bg">
        <div className="flex justify-between items-center">
          <div className="flex gap-1 items-center w-full">
            <div className="size-10 rounded-full bg-gray-200 shrink-0"></div>
            <div className="h-2 rounded bg-gray-200 w-full"></div>
          </div>
        </div>
        <div className="h-2 rounded bg-gray-200"></div>
      </CardContent>
    </Card>
  ));
};
