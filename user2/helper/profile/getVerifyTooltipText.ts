export const getVerifyTooltipText = (field: string) => {
  switch (field) {
    case 'email':
      return 'برای تغییر ایمیل خود، ابتدا کد تایید به ایمیل شما ارسال خواهد شد.';
    case 'mobile':
      return 'برای تغییر شماره موبایل، ابتدا کد تایید به شماره شما ارسال خواهد شد.';
    case 'password':
      return 'برای تغییر پسورد، ابتدا کد تایید به ایمیل و شماره موبایل شما ارسال خواهد شد.';
    default:
      return '';
  }
};
