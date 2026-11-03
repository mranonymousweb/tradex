/* eslint-disable @typescript-eslint/no-explicit-any */

import { startTransition, useState } from 'react';
import { reformatDate } from '@/helper/reformatDate';
import { useQuery } from '@tanstack/react-query';
import { Funnel, FunnelX } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { DateObject } from 'react-multi-date-picker';
import { Department } from '@/types/Ticket';
import { getDepartments } from '@/services/ticket.services';
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

interface Props {
  isFilter: boolean;
  setIsFilter: (isFilter: boolean) => void;
  setSearchQuery: React.Dispatch<React.SetStateAction<any>>;
}

export const FilterList: React.FC<Props> = ({
  isFilter,
  setIsFilter,
  setSearchQuery,
}) => {
  const t = useTranslations('ticket.filter');
  const { data: departments = [] } = useQuery<Department[]>({
    queryKey: ['departments'],
    queryFn: getDepartments,
    refetchOnMount: false,
  });
  const [dateValue, setDateValue] = useState<null | DateObject>(null);
  const [selectedStatus, setSelectedStatus] = useState('');
  const [id, setId] = useState('');
  const [selectedDepartment, setSelectedDepartment] = useState('');
  const [open, setOpen] = useState(false);

  const closeDialog = () => {
    setOpen(false);
  };

  const handleRemoveFilter = () => {
    setIsFilter(false);
    setSearchQuery(null);
  };

  const handleSubmit = () => {
    const params = new URLSearchParams();
    params.append('id', id);
    params.append('status', selectedStatus);
    params.append('department_id', selectedDepartment);
    params.append('created_at', reformatDate(dateValue));
    setIsFilter(true);
    closeDialog();
    startTransition(() => setSearchQuery(params));
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

      <Dialog open={open} onOpenChange={closeDialog}>
        <DialogContent className="w-full md:w-[450px]">
          <DialogHeader>
            <DialogTitle>{t('filter')}</DialogTitle>
          </DialogHeader>
          <DialogDescription>{t('description_header')}</DialogDescription>
          <div className="grid grid-cols-1 gap-3 mt-6">
            <div className="flex flex-col">
              <Label className="mb-2">{t('id_ticket')}</Label>
              <Input
                type="text"
                value={id}
                onChange={(e) => setId(e.target.value)}
                placeholder={t('placeholder_id')}
              />
            </div>
            <div className="flex flex-col">
              <Label className="mb-2">{t('department')}</Label>
              <Select
                value={selectedDepartment}
                onValueChange={setSelectedDepartment}
              >
                <SelectTrigger>
                  <SelectValue placeholder={t('choose')} />
                </SelectTrigger>
                <SelectContent>
                  {departments &&
                    departments.map((opt) => (
                      <SelectItem key={opt.id} value={opt.id.toString()}>
                        {opt.name}
                      </SelectItem>
                    ))}
                </SelectContent>
              </Select>
            </div>
            <div className="flex flex-col">
              <Label className="mb-2">{t('status')}</Label>
              <Select value={selectedStatus} onValueChange={setSelectedStatus}>
                <SelectTrigger>
                  <SelectValue placeholder={t('choose')} />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="new">{t('status_option.new')}</SelectItem>
                  <SelectItem value="pending">
                    {t('status_option.waiting')}
                  </SelectItem>
                  <SelectItem value="answered">
                    {t('status_option.answer_manager')}
                  </SelectItem>
                  <SelectItem value="user_answering">
                    {t('status_option.answer_user')}
                  </SelectItem>
                  <SelectItem value="closed">
                    {t('status_option.closed')}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="flex flex-col">
              <Label className="mb-2">{t('date_register')}</Label>
              <Calender onChange={(_val: DateObject) => setDateValue(_val)} />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={closeDialog}>
              {t('close')}
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
