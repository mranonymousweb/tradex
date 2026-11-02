'use client';

import { ReactNode, useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { ArrowLeft } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { Ticket } from '@/types/Ticket';
import { toAbsoluteUrl } from '@/lib/helpers';
import {
  Avatar,
  AvatarImage,
  AvatarIndicator,
  AvatarStatus,
} from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { NoData } from '@/components/ui/no-data';
import {
  Sheet,
  SheetBody,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet';
import { ContentLoader } from '@/components/common/content-loader';
import { Chat } from './Chat';

interface Props {
  tickets: Ticket[];
  isPending: boolean;
  trigger: ReactNode;
}

export function TicketList({ tickets, isPending, trigger }: Props) {
  const t = useTranslations('ticket');
  const [showChat, setShowChat] = useState(false);
  const [ticketId, setTicketId] = useState('');
  const newTickets = tickets ?? [];
  const [open, setOpen] = useState(false);

  const handleShowChat = (id: string) => {
    setTicketId(id);
    setShowChat(true);
  };

  const router = useRouter();

  const handleRedirectToAddTicket = () => {
    router.push('/ticket/add');
    setOpen(false);
  };

  return (
    <Sheet open={open} onOpenChange={setOpen}>
      <SheetTrigger asChild>{trigger}</SheetTrigger>

      {!showChat ? (
        <SheetContent
          className={`p-0 gap-0 w-full sm:w-[450px] sm:max-w-none sm:inset-5 sm:start-auto h-auto 
                    rounded-lg [&_[data-slot=sheet-close]]:text-destructive [&_[data-slot=sheet-close]]:top-5 
                    [&_[data-slot=sheet-close]]:end-3`}
        >
          <SheetHeader>
            <div className="flex items-center justify-between p-3 border-b border-border">
              <SheetTitle>
                <Button
                  type="button"
                  variant="ghost"
                  className="flex items-center gap-x-2"
                >
                  {t('new_ticket')}
                </Button>
              </SheetTitle>
              <Button
                type="button"
                size="md"
                variant="outline"
                className="ltr:mr-8 rtl:ml-8"
                onClick={handleRedirectToAddTicket}
              >
                <span className="flex items-center gap-x-2">
                  <i className="ki-filled ki-plus"></i>
                  <span>{t('register_ticket')}</span>
                </span>
              </Button>
            </div>
          </SheetHeader>

          <SheetBody className="kt-scrollable-y-auto grow space-y-1">
            {isPending ? (
              <ContentLoader />
            ) : newTickets.length === 0 ? (
              <div className="flex items-center justify-center h-full">
                <NoData />
              </div>
            ) : (
              <ul>
                {newTickets &&
                  newTickets.map((ticket) => (
                    <li
                      key={ticket?.id}
                      className="p-4 hover:bg-accent/50 cursor-pointer border-b flex gap-2"
                      onClick={() => handleShowChat(ticket.id)}
                    >
                      <Avatar className="size-9">
                        <AvatarImage
                          src={toAbsoluteUrl('/media/avatars/chat.png')}
                          alt="avatar"
                        />
                        <AvatarIndicator className="-end-2 -bottom-2">
                          <AvatarStatus
                            variant={
                              ticket.status === 'new' ? 'online' : 'offline'
                            }
                            className="size-2.5"
                          />
                        </AvatarIndicator>
                      </Avatar>
                      <div className="flex-1 flex items-center justify-between gap-8">
                        <div className="flex flex-col gap-1">
                          <div className="inline-flex gap-0.5 text-sm">
                            <span className="line-clamp-1">
                              {ticket?.title}
                            </span>
                          </div>
                          <span className="text-xs text-muted-foreground flex gap-x-1">
                            {ticket.created_at}
                          </span>
                        </div>
                        <div className="flex gap-2">
                          <Button size="sm" variant="secondary">
                            <ArrowLeft
                              size={15}
                              className="rtl:rotate-0 ltr:rotate-180"
                            />
                          </Button>
                        </div>
                      </div>
                    </li>
                  ))}
              </ul>
            )}
          </SheetBody>
        </SheetContent>
      ) : (
        <Chat
          key={ticketId}
          id={ticketId}
          hideChat={() => setShowChat(false)}
        />
      )}
    </Sheet>
  );
}
