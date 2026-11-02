interface Coin {
  symbol: string;
  last: number; // یا string بسته به API
}
interface SwapTransactionsArgs {
  inputReceived: string;
  inputPayment: string;
  selectedPaymentCoin: Coin;
  selectedReceivedCoin: Coin;
  isFocus: boolean;
  setInputReceived: (value: string) => void;
  setInputPayment: (value: string) => void;
}

export const swapTransactions = ({
  inputReceived,
  inputPayment,
  selectedPaymentCoin,
  selectedReceivedCoin,
  isFocus,
  setInputReceived,
  setInputPayment,
}: SwapTransactionsArgs) => {
  if (!selectedPaymentCoin || !selectedReceivedCoin) return;

  const paymentPrice = selectedPaymentCoin.last;
  const receivedPrice = selectedReceivedCoin.last;

  if (!paymentPrice || !receivedPrice) return;

  const isPaymentIRT = selectedPaymentCoin.symbol === 'IRT';
  const isReceivedIRT = selectedReceivedCoin.symbol === 'IRT';

  let result: number | null = null;

  if (!isFocus) {
    if (isPaymentIRT) {
      result = Number(inputPayment) / (receivedPrice * paymentPrice);
    } else if (isReceivedIRT) {
      result = Number(inputPayment) * paymentPrice * receivedPrice;
    } else {
      result = (Number(inputPayment) * paymentPrice) / receivedPrice;
    }

    if (!isNaN(result) && inputPayment) setInputReceived(result.toString());
  } else {
    if (isPaymentIRT) {
      result = Number(inputReceived) * receivedPrice * paymentPrice;
    } else if (isReceivedIRT) {
      result = Number(inputReceived) / (paymentPrice * receivedPrice);
    } else {
      result = (Number(inputReceived) * receivedPrice) / paymentPrice;
    }

    if (!isNaN(result)) setInputPayment(result.toString());
  }
};
