'use client';

import { useTranslations } from 'next-intl';
import { Device } from '@/types/profile';
import { toAbsoluteUrl } from '@/lib/helpers';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';
import { DestroyDevice } from './destroy-device';

interface ITrustedDevicesItem {
  logo: string;
  browser: string;
  location: string;
  flag: string;
  device: string;
  datetime: string;
}
type ITrustedDevicesItems = Array<ITrustedDevicesItem>;

const Devices = ({ devices }: { devices: Device[] }) => {
  const t = useTranslations('profile');

  const renderItem = (item: Device, index: number) => {
    return (
      <TableRow key={index}>
        <TableCell className="min-w-48 w-48">
          <div className="flex items-center gitem gap-2.5">
            <img
              src={toAbsoluteUrl(`/media/ui/website.png`)}
              className="h-10"
              alt="image"
            />
            <div className="flex flex-col">
              <div className="text-sm font-medium text-mono hover:text-primary-active mb-px">
                {item?.browser}
              </div>
              <div className="flex gap-1.5">
                <span className="text-xs text-secondary-foreground">
                  {item?.ip_details
                    ? item?.ip_details?.country + ',' + item?.ip_details?.city
                    : '--'}
                </span>
              </div>
            </div>
          </div>
        </TableCell>
        <TableCell className="min-w-56 text-secondary-foreground font-normal">
          {item?.device} - {item?.platform}
          <br />
          {item?.last_used_at}
        </TableCell>
        <TableCell className="pr-7.5! min-w-16 text-end">
          <DestroyDevice id={item?.id} />
        </TableCell>
      </TableRow>
    );
  };

  return (
    <Card className="min-w-full">
      <CardHeader id="device">
        <CardTitle>{t('session')}</CardTitle>
      </CardHeader>
      <CardContent className="kt-scrollable-x-auto p-0">
        <div className="kt-scrollable-auto">
          <Table className="table-fixed align-middle text-secondary-foreground font-medium text-sm">
            <TableBody>
              {devices?.map((item, index) => {
                return renderItem(item, index);
              })}
            </TableBody>
          </Table>
        </div>
      </CardContent>
    </Card>
  );
};

export { Devices, type ITrustedDevicesItem, type ITrustedDevicesItems };
