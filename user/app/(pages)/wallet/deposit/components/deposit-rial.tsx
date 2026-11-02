/* eslint-disable @typescript-eslint/no-explicit-any */
import { useContext, useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { decimalPipe } from '@/helper/decimalPipe';
import { WalletContext } from '@/store/wallet';
import { CircleAlert, CircleX, Copy, FileText, Upload } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { normalizeGateways } from '@/helper/wallet/format-gateway';
import { BanksItems, Cards } from '@/types/wallet';
import { Alert } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';
import InputCurrency from '@/components/ui/input-currency';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Table, TableBody, TableCell, TableRow } from '@/components/ui/table';

interface Props {
    currency: any;
    form: any;
    setAutoCard: (val: boolean) => void;
}

export const DepositRial = ({ currency, form, setAutoCard }: Props) => {
    const t = useTranslations('wallet.deposit');
    const { packageInfo } = useContext(WalletContext);
    const [files, setFiles] = useState<File[]>([]);
    const paymentType = form.watch('type');
    const transaction = form.watch('transaction');
    const isCard = paymentType === 'card' || paymentType === 'automatic_card';
    const listCard: Cards[] = paymentType ? currency['cards'] : [];
    const listGateway = paymentType ? normalizeGateways(currency?.gateway) : [];

    const bankInfo = isCard
        ? listCard?.find((card) => card.id === Number(transaction))
        : null;

    const items = [
        {
            label: `${t('bank.bank_owner')} :`,
            info: bankInfo?.card_owner,
            copied: false,
        },
        {
            label: `${t('bank.bank_name')} :`,
            info: bankInfo?.bank.name,
            copied: false,
        },
        {
            label: `${t('bank.bank_card')} :`,
            info: bankInfo?.card_number,
            copied: true,
        },
        {
            label: `${t('bank.bank_ibn')} :`,
            info: `IR${bankInfo?.sheba}`,
            copied: true,
        },
    ];

    const handleCopy = (value: string) => {
        window.navigator.clipboard.writeText(value);
        toast.success(t('success_copy'));
    };

    const renderItems = (item: BanksItems, index: number) => {
        return (
            <TableRow key={index} className="border-0 flex flex-col md:flex-row">
                <TableCell className="text-sm text-secondary-foreground pb-3 pe-4 lg:pe-10 py-2">
                    {item.label}
                </TableCell>
                <TableCell className="text-sm text-mono pb-3 py-2 flex items-center gap-x-2">
                    {item.info}

                    {item.copied && (
                        <button onClick={() => handleCopy(item.info)} className="pb-1">
                            <Copy size={15} className="text-secondary-foreground" />
                        </button>
                    )}
                </TableCell>
            </TableRow>
        );
    };

    const handleFile = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        const MAX_SIZE = 10 * 1024 * 1024;

        if (file) {
            if (file.size > MAX_SIZE) {
                return;
            }
            form.setValue('file', file);
            setFiles([...files, file]);
        }
    };

    const handleRemoveFile = (id: number) => {
        const file = files.filter((file, i) => i !== id);
        setFiles(file);
        form.setValue('file', '');
    };

    const handleSelect = (type: string) => {
        const isAutoCard = type === 'automatic_card';
        form.setValue('type', type);
        setAutoCard(isAutoCard);
    };

    return (
        <>
            <FormField
                control={form.control}
                name="type"
                render={({ field }) => (
                    <FormItem className="flex md:flex-row md:items-center">
                        <FormLabel className="flex w-full max-w-56">
                            {t('type_choose_deposit')}
                        </FormLabel>
                        <div className="flex flex-col gap-y-1 w-full">
                            <FormControl className="grow">
                                <Select
                                    disabled={!packageInfo.deposit}
                                    value={field.value}
                                    onValueChange={handleSelect}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder={t('choose')} />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {currency?.payments.map((pay: any) => (
                                            <SelectItem key={pay} value={pay}>
                                                {pay === 'card' || pay === 'automatic_card'
                                                    ? t('table.card_card')
                                                    : t('table.gateway')}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormMessage />
                            <div className="flex flex-col md:flex-row justify-between">
                                <div className="mt-1 flex items-center gap-x-1.5 text-[#FF6F1E]">
                                    <span className="text-2xs">{t('min_deposit')} :</span>
                                    <div className="flex items-center text-xs gap-x-1">
                                        <span>
                                            {currency
                                                ? decimalPipe(currency?.networks?.min_deposit, 0)
                                                : 0}
                                        </span>
                                        <small className="text-2xs">{currency?.symbol}</small>
                                    </div>
                                </div>
                                <div className="mt-1 flex items-center gap-x-1.5 text-[#FF6F1E]">
                                    <span className="text-2xs">{t('max_deposit')} :</span>
                                    <div className="flex items-center text-xs gap-x-1">
                                        <span>
                                            {currency
                                                ? decimalPipe(currency?.networks?.max_deposit, 0)
                                                : 0}
                                        </span>
                                        <small className="text-2xs">{currency?.symbol}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </FormItem>
                )}
            />

            <FormField
                control={form.control}
                name="transaction"
                render={({ field }) => (
                    <FormItem className="flex md:flex-row md:items-center">
                        <FormLabel className="flex w-full max-w-56">
                            {isCard ? t('choose_bank') : t('table.gateway')}
                        </FormLabel>
                        <div className="flex flex-col gap-y-1 w-full">
                            <FormControl>
                                <Select
                                    disabled={!packageInfo.deposit}
                                    value={field.value}
                                    onValueChange={field.onChange}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder={t('choose')} />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {isCard
                                            ? listCard?.map((card, i) => (
                                                <SelectItem key={i} value={String(card.id)}>
                                                    <div className="flex items-center gap-x-1">
                                                        <Image
                                                            src={card.bank.icon}
                                                            width={24}
                                                            height={24}
                                                            className="w-6 h-6 rounded-full"
                                                            alt="icon-currency"
                                                        />
                                                        {card.bank.name}
                                                    </div>
                                                </SelectItem>
                                            ))
                                            : listGateway?.map((pay, i) => (
                                                <SelectItem key={i} value={`${pay.value}`}>
                                                    {pay.key}
                                                </SelectItem>
                                            ))}
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormMessage />
                        </div>
                    </FormItem>
                )}
            />

            {paymentType !== 'automatic_card' && (
                <FormField
                    control={form.control}
                    name="amount"
                    render={({ field }) => (
                        <FormItem className="flex md:flex-row md:items-center">
                            <FormLabel className="flex w-full max-w-56">
                                {t('payment_amount')} ({currency?.symbol})
                            </FormLabel>
                            <div className="flex flex-col gap-y-1 w-full">
                                <FormControl>
                                    <InputCurrency
                                        type="text"
                                        value={field.value}
                                        disabled={!packageInfo.deposit}
                                        onValueChange={field.onChange}
                                        placeholder={t('input_placeholder')}
                                    />
                                </FormControl>
                                <FormMessage />
                            </div>
                        </FormItem>
                    )}
                />
            )}

            {bankInfo && (
                <div className="flex flex-wrap lg:flex-nowrap gap-2.5">
                    <Label className="flex w-full max-w-56"></Label>
                    <div className="grow">
                        <Card>
                            <CardHeader>
                                <CardTitle>{t('bank.title')}</CardTitle>
                            </CardHeader>
                            <CardContent className="pt-3.5 pb-3.5">
                                <Table>
                                    <TableBody>
                                        {items.map((item: any, index) => {
                                            return renderItems(item, index);
                                        })}
                                    </TableBody>
                                </Table>
                            </CardContent>
                            <CardFooter className="flex flex-col p-2">
                                {paymentType === 'card' ? (
                                    <>
                                        <div className="flex items-center justify-between p-5 w-full">
                                            <div className="flex flex-col gap-2">
                                                <div className="flex items-center gap-2.5">
                                                    <Link
                                                        href="#"
                                                        className="text-sm font-medium text-mono hover:text-primary-active"
                                                    >
                                                        {t('bank.upload_file')}
                                                    </Link>
                                                    <Badge variant="destructive" appearance="outline">
                                                        {t('bank.guide_upload')}
                                                    </Badge>
                                                </div>
                                                <div className="text-xs text-secondary-foreground">
                                                    {t('bank.description_upload')}
                                                </div>
                                            </div>
                                            <div className="flex items-center gap-1.5 shrink-0">
                                                <Button
                                                    type="button"
                                                    disabled={files.length >= 1}
                                                    className="p-0"
                                                    variant="outline"
                                                >
                                                    <label
                                                        htmlFor="uploadFile"
                                                        className="px-4 py-2 flex gap-x-1"
                                                    >
                                                        <input
                                                            type="file"
                                                            onChange={handleFile}
                                                            id="uploadFile"
                                                            className="hidden"
                                                            accept=".png,.jpg"
                                                        />
                                                        <Upload size={20} />
                                                        {t('bank.upload_file')}
                                                    </label>
                                                </Button>
                                            </div>
                                        </div>
                                        <ul className="w-full flex items-center gap-1 flex-wrap">
                                            {files.map((file, i) => (
                                                <li
                                                    key={i}
                                                    className="flex items-center gap-x-1 border p-2 rounded-sm"
                                                >
                                                    <FileText
                                                        size={16}
                                                        className="text-base text-green-500"
                                                    />
                                                    <span className="text-sm text-mono text-nowrap">
                                                        {file.name}
                                                    </span>
                                                    <button
                                                        type="button"
                                                        onClick={() => handleRemoveFile(i)}
                                                    >
                                                        <CircleX
                                                            size={15}
                                                            className="text-base text-red-500 ms-2.5"
                                                        />
                                                    </button>
                                                </li>
                                            ))}
                                        </ul>
                                    </>
                                ) : (
                                    <Alert appearance="light" variant="warning">
                                        <p className="text-2sm flex gap-x-2">
                                            <CircleAlert size={17} className="text-yellow-600" />
                                            {t('bank.alert_card_deposit_1')}
                                            <br />
                                            {t('bank.alert_card_deposit_2')}
                                        </p>
                                    </Alert>
                                )}
                            </CardFooter>
                        </Card>
                    </div>
                </div>
            )}
        </>
    );
};
