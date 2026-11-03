export interface Department {
  id: string;
  name: string;
}

export type TicketStatus =
  | 'new'
  | 'pending'
  | 'answered'
  | 'user_answered'
  | 'closed';

export interface Ticket {
  id: string;
  title: string;
  description: string;
  created_at: string;
  department: Department;
  status: TicketStatus;
  messages: Message[];
  files: TicketFile[];
}
export interface PaginationMeta {
  last_page: number;
  total: number;
}

export interface TicketsType {
  data?: Ticket[];
  meta: PaginationMeta;
}

export interface Message {
  id: number;
  ticket_id: number;
  user_id: number;
  user_type: string;
  from: 'admin' | 'user';
  user: User;
  message: string;
  files: TicketFile[];
  status: string;
  seen_at: string | null;
  deleted_at: string | null;
  created_at: string;
  updated_at: string;
}

export interface TicketFile {
  id: number;
  name: string;
  path: string;
}

export interface User {
  avatar: string;
  email: string;
  name: string;
  family: string;
  mobile: string;
  address: string;
}

export interface TicketRow {
  id: string;
  title: string;
  status: TicketStatus;
  created_at: string;
  department: { name: string };
}
