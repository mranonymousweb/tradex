/* eslint-disable @typescript-eslint/no-explicit-any */
import { Dispatch, SetStateAction, startTransition, useState } from 'react';
import { Funnel, FunnelX } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { Button } from '@/components/ui/button';
import { Calender } from '@/components/ui/date-picker';
import DialogContent, {
  Dialog,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';

export const FilterList = ({
  setSearchQuery,
  isFilter,
  setIsFilter,
}: {
  isFilter: boolean;
  setIsFilter: (isFilter: boolean) => void;
  setSearchQuery: Dispatch<SetStateAction<any>>;
}) => {
  const t = useTranslations('swap.table.dialog_filter');
  const [value, setValue] = useState<any>(null);
  const [selectedPayCoin, setSelectedPayCoin] = useState<any>('');
  const [selectedReceiveCoin, setSelectedReceiveCoin] = useState<any>('');
  const [open, setOpen] = useState(false);
  const [status, setStatus] = useState('');
  const [text, setText] = useState('');

  const closeDialog = () => {
    setOpen(false);
  };

  const handleRemoveFilter = () => {
    setIsFilter(false);
    setSearchQuery(null);
  };

  const handleSubmit = () => {
    const query = new URLSearchParams();

    if (status) query.append('status', status);
    if (text) query.append('id', text);
    if (selectedPayCoin)
      query.append('fromCurrencySymbol', selectedPayCoin.toUpperCase());
    if (selectedReceiveCoin)
      query.append('toCurrencySymbol', selectedReceiveCoin.toUpperCase());
    if (value) query.append('date', value);
    const queryString = query.toString();

    setIsFilter(true);
    startTransition(() => setSearchQuery(queryString));
    closeDialog();
  };

  return (
    <>
      {!isFilter ? (
        <Button variant="outline" onClick={() => setOpen(true)}>
          <Funnel />
          {t('filter')}
        </Button>
      ) : (
        <Button variant="destructive" onClick={handleRemoveFilter}>
          <FunnelX />
          {t('remove_filter')}
        </Button>
      )}

      <Dialog open={open} onOpenChange={() => setOpen(false)}>
        <DialogContent className="w-full md:w-[450px]">
          <DialogHeader>
            <DialogTitle>{t('filter')}</DialogTitle>
          </DialogHeader>
          <DialogDescription>{t('description')}</DialogDescription>
          <div className="grid grid-cols-1 gap-3 mt-6">
            <div className="flex flex-col">
              <Label className="mb-2">{t('id')}</Label>
              <Input
                type="text"
                value={text}
                onChange={(e) => setText(e.target.value)}
                placeholder={t('placeholder_id')}
              />
            </div>

            <div className="flex flex-col">
              <Label className="mb-2">{t('payment_currency')}</Label>
              <div className="grow relative">
                <Input
                  type="text"
                  value={selectedPayCoin}
                  onChange={(e) => setSelectedPayCoin(e.target.value)}
                  placeholder={t('placeholder_currency')}
                />
              </div>
            </div>

            <div className="flex flex-col">
              <Label className="mb-2">{t('received_currency')}</Label>
              <div className="grow relative">
                <Input
                  type="text"
                  value={selectedReceiveCoin}
                  onChange={(e) => setSelectedReceiveCoin(e.target.value)}
                  placeholder={t('placeholder_currency')}
                />
              </div>
            </div>

            <div className="flex flex-col">
              <Label className="mb-2">{t('status')}</Label>
              <Select value={status} onValueChange={setStatus}>
                <SelectTrigger>
                  <SelectValue placeholder={t('placeholder_select')} />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="accepted_by_site">
                    {t('successful')}
                  </SelectItem>
                  <SelectItem value="rejected_by_site">
                    {t('unSuccessful')}
                  </SelectItem>
                  <SelectItem value="waiting">{t('waiting')}</SelectItem>
                  <SelectItem value="waiting_by_supplier">
                    {t('waiting_by_supplier')}
                  </SelectItem>
                  <SelectItem value="rejected_by_supplier">
                    {t('rejected_by_supplier')}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="flex flex-col">
              <Label className="mb-2">{t('date_register')}</Label>
              <Calender onChange={setValue} />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={closeDialog}>
              {t('cancel')}
            </Button>
            <Button
              type="button"
              variant="primary"
              className="mb-2 md:mb-0"
              onClick={handleSubmit}
            >
              {t('search')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
};
