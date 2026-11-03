import { charts, sliders } from '@/services/dashboard.services';
import {
  ChannelStats,
  Notifications,
} from '@/app/(pages)/dashboard/components';
import { Package } from './light-sidebar/components/package';
import { DashboardSlider } from './light-sidebar/components/slider';
import { SwapChart } from './light-sidebar/components/swapChart';
import { Tickets } from './light-sidebar/components/tickets';

export default async function DashboardContent() {
  const slideImages = await sliders();
  const chartsData = await charts();

  return (
    <div className="grid gap-2 lg:gap-3">
      <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-y-5 lg:gap-3 items-stretch">
        <div className="lg:col-span-1">
          <div className="grid grid-cols-2 gap-3 h-full items-stretch">
            <ChannelStats />
          </div>
        </div>
        <div className="xl:col-span-2 h-64">
          <DashboardSlider slideImages={slideImages} />
        </div>
      </div>
      <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-3 items-stretch">
        <div className="lg:col-span-1">
          <Package limit={4} />
        </div>
        <div className="xl:col-span-2">
          <SwapChart chartsData={chartsData} />
        </div>
      </div>
      <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-3 items-stretch">
        <div className="lg:col-span-1">
          <Notifications />
        </div>
        <div className="xl:col-span-2">
          <Tickets />
        </div>
      </div>
    </div>
  );
}
