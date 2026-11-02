import { T } from '@/types';

export function getTicketStatusText(status: string, t: T): string {
  switch (status) {
    case 'new':
      return t('status_option.new');
    case 'pending':
      return t('status_option.pending');
    case 'answered':
      return t('status_option.answered_manager');
    case 'user_answering':
      return t('status_option.user_answering');
    case 'closed':
      return t('status_option.closed');
    default:
      return 'نامشخص';
  }
}
