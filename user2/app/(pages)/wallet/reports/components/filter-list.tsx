/* eslint-disable @typescript-eslint/no-explicit-any */
import { Dispatch, SetStateAction, startTransition, useState } from 'react';
import { reformatDate } from '@/helper/reformatDate';
import { Funnel, FunnelX } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { DateObject } from 'react-multi-date-picker';
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
    const t = useTranslations('wallet.deposit.table.dialog_filter');
    const [value, setValue] = useState<null | DateObject>(null);
    const [open, setOpen] = useState(false);
    const [status, setStatus] = useState('');
    const [text, setText] = useState('');
    const [type, setType] = useState('');
    const [currency, setCurrency] = useState('');
    const params = new URLSearchParams();

    const closeDialog = () => {
        setOpen(false);
    };

    const handleRemoveFilter = () => {
        setIsFilter(false);
        setSearchQuery(null);
    };

    const handleSubmit = () => {
        params.append('status', status);
        params.append('txt_id', text);
        params.append('type', type);
        params.append('currency', currency);
        params.append('created_at', reformatDate(value));
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
                            <Label className="mb-2">{t('id_transaction')}</Label>
                            <Input
                                type="text"
                                value={text}
                                onChange={(e) => setText(e.target.value)}
                                placeholder={t('placeholder_text')}
                            />
                        </div>
                        <div className="flex flex-col">
                            <Label className="mb-2">{t('currency')}</Label>
                            <Input
                                type="text"
                                value={currency}
                                onChange={(e) => setCurrency(e.target.value)}
                                placeholder={t('placeholder_currency')}
                            />
                        </div>
                        <div className="flex flex-col">
                            <Label className="mb-2">{t('status')}</Label>
                            <Select value={status} onValueChange={setStatus}>
                                <SelectTrigger>
                                    <SelectValue placeholder={t('placeholder_select')} />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="1">
                                        {t('status_option.waiting')}
                                    </SelectItem>
                                    <SelectItem value="2">{t('status_option.active')}</SelectItem>
                                    <SelectItem value="3">
                                        {t('status_option.canceled')}
                                    </SelectItem>
                                    <SelectItem value="6">
                                        {t('status_option.failed')}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="flex flex-col">
                            <Label className="mb-2">{t('type_transaction')}</Label>
                            <Select value={type} onValueChange={setType}>
                                <SelectTrigger>
                                    <SelectValue placeholder={t('placeholder_select')} />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="1">{t('deposit')}</SelectItem>
                                    <SelectItem value="2">{t('withdrawal')}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="flex flex-col">
                            <Label className="mb-2">{t('date_register')}</Label>
                            <Calender onChange={(_val: DateObject) => setValue(_val)} />
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
