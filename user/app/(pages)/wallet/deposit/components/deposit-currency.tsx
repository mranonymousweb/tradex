/* eslint-disable @typescript-eslint/no-explicit-any */
import {
  startTransition,
  useActionState,
  useContext,
  useEffect,
  useState,
} from 'react';
import { decimalPipe } from '@/helper/decimalPipe';
import { WalletContext } from '@/store/wallet';
import { Copy, QrCode } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { UseFormReturn } from 'react-hook-form';
import QRCode from 'react-qr-code';
import { toast } from 'sonner';
import { Network, Supplier, WalletRecive } from '@/types/wallet';
import { receiveAddress } from '@/services/wallet.services';
import { Button } from '@/components/ui/button';
import DialogContent, {
  Dialog,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';
import { Input, InputWrapper } from '@/components/ui/input';
import InputCurrency from '@/components/ui/input-currency';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinners';
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from '@/components/ui/tooltip';
import { FormSchemaType } from './form-schema';

export const DepositCurrency = ({
  form,
  filterCurrency,
  address,
  setAddress,
}: {
  form: UseFormReturn<FormSchemaType>;
  filterCurrency: any;
  address: WalletRecive | null;
  setAddress: (item: WalletRecive | null) => void;
}) => {
  const t = useTranslations('wallet.deposit');
  const { packageInfo, selectedCoin, plugins } = useContext(WalletContext);
  const [showModal, setShowModal] = useState(false);
  const [amount, setAmount] = useState<string | undefined>('');
  const [state, action, pending] = useActionState(receiveAddress, null);

  //Plugins
  const has_plugin_deposit = plugins?.deposit_request_plugin;

  useEffect(() => {
    setAddress(state?.data);
    setShowModal(false);
    if (state?.error) {
      toast.error(t('not_found_network'));
    }
  }, [state, setAddress, t]);

  const network = form.watch('networkId');
  const filterNetwork = network
    ? filterCurrency?.networks.find((cl: Network) => cl.id === +network)
    : undefined;

  const handleNetwork = (value: string) => {
    form.setValue('networkId', value);
    const values = form.getValues();

    //Enable Pluins
    const hasShare = filterCurrency?.networks.find(
      (net: Network) => net.id === +value,
    );
    if (has_plugin_deposit && hasShare?.wallet_supplier_type === 'share') {
      setShowModal(true);
      return;
    }

    //Unnable Plugin
    startTransition(() =>
      action({
        currencyId: values.currencyId,
        networkId: values.networkId,
      }),
    );
  };

  const handleCopyText = (value: string) => {
    navigator.clipboard.writeText(value);
    toast.success(t('success_copy'));
  };

  const handleAmountTransaction = () => {
    const values = form.getValues();

    startTransition(() =>
      action({
        currencyId: values.currencyId,
        networkId: values.networkId,
        amount,
      }),
    );
  };

  return (
    <>
      <FormField
        control={form.control}
        name="networkId"
        render={({ field }) => (
          <FormItem className="flex md:flex-row md:items-center">
            <FormLabel className="flex w-full max-w-56">
              {t('select_network')}
            </FormLabel>
            <div className="flex flex-col w-full gap-y-1">
              <FormControl>
                <Select
                  disabled={!packageInfo.deposit}
                  value={field.value ?? undefined}
                  onValueChange={(val) => handleNetwork(val)}
                >
                  <SelectTrigger>
                    <SelectValue placeholder={t('choose')} />
                  </SelectTrigger>
                  <SelectContent>
                    {filterCurrency ? (
                      filterCurrency.networks.filter((network: Network) => network.is_deposit_able).map((network: Network) => (
                        <SelectItem key={network.id} value={`${network.id}`}>
                          {network.symbol}
                        </SelectItem>
                      ))
                    ) : (
                      <span className="text-xs">{t('no_result')}</span>
                    )}
                  </SelectContent>
                </Select>
              </FormControl>
              <FormMessage />

              {field.value && (
                <div className="mt-1 flex items-center gap-x-1.5 text-[#FF6F1E]">
                  <span className="text-2xs">{t('min_deposit')} :</span>
                  <div className="flex items-center text-xs gap-x-1">
                    <span>
                      {filterNetwork
                        ? decimalPipe(filterNetwork?.min_deposit, 6)
                        : 0}
                    </span>
                    <small className="text-2xs">{filterCurrency?.symbol}</small>
                  </div>
                </div>
              )}
            </div>
          </FormItem>
        )}
      />
      <div className="flex items-center flex-wrap lg:flex-nowrap gap-2.5">
        <Label className="flex w-full max-w-56">{t('address_wallet')}</Label>
        <div className="grow">
          <InputWrapper>
            <Input
              type="text"
              disabled={!packageInfo.deposit}
              defaultValue={
                pending ? '....' : selectedCoin && (address?.address ?? '')
              }
              readOnly
            />
            <Button
              type="button"
              variant="dim"
              mode="icon"
              className="-me-2"
              onClick={() => handleCopyText(address?.address ?? '')}
            >
              <Copy size={16} />
            </Button>
            <Tooltip>
              <TooltipTrigger>
                <QrCode color="oklch(55.1% 0.027 264.364)" />
              </TooltipTrigger>
              <TooltipContent className="w-40 h-40 bg-background border border-foreground">
                <QRCode
                  style={{ height: 'auto', maxWidth: '100%', width: '100%' }}
                  value={address?.address ?? ''}
                />
              </TooltipContent>
            </Tooltip>
          </InputWrapper>
        </div>
      </div>

      <div
        className={`flex items-center flex-wrap lg:flex-nowrap gap-2.5 ${!filterNetwork?.has_tag && '!hidden'}`}
      >
        <Label className="flex w-full max-w-56">{t('tag')}</Label>
        <div className="grow">
          <InputWrapper>
            <Input
              type="text"
              disabled={!packageInfo.deposit}
              defaultValue={pending ? '....' : selectedCoin && address?.tag}
              readOnly
            />
            <Button
              type="button"
              variant="dim"
              mode="icon"
              className="-me-2"
              onClick={() => handleCopyText(address?.tag ?? '')}
            >
              <Copy size={16} />
            </Button>
            <Tooltip>
              <TooltipTrigger>
                <QrCode color="oklch(55.1% 0.027 264.364)" />
              </TooltipTrigger>
              <TooltipContent className="w-40 h-40 bg-background border border-foreground">
                <QRCode
                  style={{ height: 'auto', maxWidth: '100%', width: '100%' }}
                  value={address?.tag ?? ''}
                />
              </TooltipContent>
            </Tooltip>
          </InputWrapper>
        </div>
      </div>

      {address?.supplier === Supplier.HASSUPPLIER && (
        <FormField
          control={form.control}
          name="txId"
          render={({ field }) => (
            <FormItem className="flex md:flex-row md:items-center">
              <FormLabel className="flex w-full max-w-56">
                {' '}
                {t('id_transaction')} (txid)
              </FormLabel>
              <div className="flex flex-col w-full gap-y-1">
                <FormControl>
                  <Input
                    type="text"
                    value={field.value ?? undefined}
                    onChange={field.onChange}
                    disabled={!packageInfo.deposit}
                    placeholder={t('enter_id')}
                  />
                </FormControl>
                <FormMessage />
              </div>
            </FormItem>
          )}
        />
      )}

      <Dialog open={showModal} onOpenChange={() => setShowModal(false)}>
        <DialogContent className="w-[450px]">
          <DialogHeader>
            <DialogTitle>{t('payment_amount')}</DialogTitle>
          </DialogHeader>
          <DialogDescription>{t('info_description')}</DialogDescription>
          <div className="flex flex-col items-center">
            <div className="w-full">
              <Label>{t('payment_amount')}</Label>
              <InputCurrency
                type="text"
                value={amount}
                onValueChange={setAmount}
                placeholder={t('enter_amount')}
              />
            </div>
          </div>
          <DialogFooter>
            <Button
              type="button"
              variant="secondary"
              onClick={() => setShowModal(false)}
            >
              {t('table.dialog_filter.cancel')}
            </Button>
            <Button
              type="button"
              onClick={handleAmountTransaction}
              disabled={pending}
            >
              {pending ? <Spinner className="size-4 animate-spin" /> : null}
              {t('confirm')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
};
