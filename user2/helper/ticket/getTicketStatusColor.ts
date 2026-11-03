export function getTicketStatusColor(status: string): string {
  switch (status) {
    case 'new':
      return 'text-green-500';
    case 'closed':
      return 'text-destructive';
    default:
      return 'text-yellow-500';
  }
}
