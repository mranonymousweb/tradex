export interface Notification {
  id: string;
  title: string;
  description: string;
  created_at: string;
  read: boolean;
  section?: 'normal' | 'tape' | 'pop_up';
  status?: 'active' | 'inactive';
}
export interface PaginationMeta {}
export interface NotificationsType {
  data?: Notification[];
  meta: {
    last_page: number;
    total: number;
  };
}

export interface NotificationRow {
  id: string;
  title: string;
  description: string;
  created_at: string;
  read: boolean;
}
