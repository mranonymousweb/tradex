import React, { useState } from 'react';
import Link from 'next/link';
import { tableOrders } from '@/helper/spot/tableOrders';
import { MainSpotContext } from '@/store/trade-spot';
import { useTranslations } from 'next-intl';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { OrderHistory } from './order-history';
import { OpenOrders } from './table-open-orders';
import { PendingOrders } from './table-pending-orders';

enum Tab {
  PENDING = 'PENDING',
  OPEN_ORDER = 'OPEN_ORDER',
  HISTORY = 'HISTORY',
}

export const TableOrders = () => {
  const t = useTranslations('spot');
  const { pair, userId } = React.useContext(MainSpotContext);
  const [showAll, setShowAll] = useState(false);
  const orders = userId ? tableOrders(`${userId}-${!showAll && pair}`) : null;
  const [tabActive, setTabActive] = React.useState<Tab>(Tab.PENDING);

  if (!userId) {
    return (
      <div className="bg-background rounded-lg my-1 p-6 text-center">
        <Button variant="outline" size="sm" asChild>
          <Link href="/signin">{t('login')}</Link>
        </Button>
      </div>
    );
  }

  const handleActiveClass = (tab: Tab): string => {
    return tabActive === tab
      ? 'pb-3 text-foreground active_tab'
      : 'text-muted-foreground pb-3';
  };

  return (
    <div className="bg-background h-full md:h-auto rounded-lg my-1" >
      <div className="flex justify-between items-center border-b p-3 pb-0" dir='ltr'>
        <div className="flex  items-center space-x-3 text-sm font-medium">
          <button
            type="button"
            className={handleActiveClass(Tab.PENDING)}
            onClick={() => setTabActive(Tab.PENDING)}
          >
            {t('pending_order')}
          </button>
          <button
            type="button"
            className={handleActiveClass(Tab.OPEN_ORDER)}
            onClick={() => setTabActive(Tab.OPEN_ORDER)}
          >
            {t('open_order')}
          </button>
          <button
            type="button"
            className={handleActiveClass(Tab.HISTORY)}
            onClick={() => setTabActive(Tab.HISTORY)}
          >
            {t('order_history')}
          </button>
        </div>
        <div className="hidden md:flex items-center gap-x-2 pb-2">
          <Checkbox
            checked={showAll}
            onCheckedChange={() => setShowAll(!showAll)}
          />
          <span className="text-sm pt-1 font-medium text-muted-foreground">
            {t('show_all_market')}
          </span>
        </div>
      </div>

      {/* {!isPending && <ContentLoader />} */}
      {tabActive === Tab.PENDING && (
        <PendingOrders updateOrder={orders} showAll={showAll} />
      )}
      {tabActive === Tab.OPEN_ORDER && (
        <OpenOrders updateOrder={orders} showAll={showAll} />
      )}
      {tabActive === Tab.HISTORY && <OrderHistory showAll={showAll} />}
    </div>
  );
};
