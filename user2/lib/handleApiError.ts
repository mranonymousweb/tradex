import { ApiError, ApiErrorType } from './api-error';

export function handleApiError(
  err: unknown,
  messages?: Partial<Record<ApiErrorType, string>>,
): { success: false; message: string; error: [string, unknown][] } {
  const defaultMessages: Record<ApiErrorType, string> = {
    NO_CONNECTION: 'اتصال به اینترنت برقرار نیست',
    TIMEOUT: 'سرور پاسخ نمی‌دهد، دوباره تلاش کنید',
    UNAUTHORIZED: 'دسترسی غیرمجاز',
    SERVER_ERROR: 'خطای سرور، لطفاً بعداً تلاش کنید',
    CLIENT_ERROR: 'درخواست نامعتبر',
    UNKNOWN: 'لطفاً اتصال اینترنت خود را بررسی کرده و دوباره تلاش کنید.',
  };

  if (err instanceof ApiError) {
    const msg = messages?.[err.type] ?? defaultMessages[err.type];
    return {
      success: false,
      message: msg,
      error: Object.entries({ message: msg }),
    };
  }
  if (err instanceof Error && err.message) {
    return {
      success: false,
      message: err.message,
      error: Object.entries({ message: err.message }),
    };
  }

  const msg = messages?.UNKNOWN ?? defaultMessages.UNKNOWN;
  return {
    success: false,
    message: msg,
    error: Object.entries({
      message: messages?.UNKNOWN ?? defaultMessages.UNKNOWN,
    }),
  };
}
