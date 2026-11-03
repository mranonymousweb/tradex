export function statusText(status: string): string {
  switch (status) {
    case 'new':
      return 'جدید';
    case 'pending':
      return 'در حال بررسی';
    case 'answered':
      return 'پاسخ مدیر';
    case 'user_answering':
      return 'پاسخ کاربر';
    case 'closed':
      return 'بسته شده';
    default:
      return 'نامشخص';
  }
}
