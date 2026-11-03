type UserBank = {
  status: string;
  [key: string]: unknown;
};

type Gate = {
  [key: string]: string;
};

type Currency = {
  networks?: {
    deposit?: {
      gate?: {
        gates?: Gate;
      };
      receipt?: {
        receipts?: Record<string, unknown>[];
      };
    };
  };
};

export const userBankConfirm = (userBanks: UserBank[]) => {
  const banks = userBanks?.filter((bank) => bank.status === 'accepted');
  return banks;
};

export const gateType = (currency: Currency) => {
  const gates = currency?.networks?.deposit?.gate?.gates;
  const gate = Object.entries(gates ?? {}).map(([value, symbol], index) => ({
    id: index,
    value,
    symbol,
  }));
  return gate;
};

export const selectedBankAdmin = (currency: Currency) => {
  const receipts = currency?.networks?.deposit?.receipt?.receipts;
  const bankOptions = receipts?.map((receipt, index: number) => ({
    id: index + 1,
    ...receipt,
  }));
  return bankOptions;
};
