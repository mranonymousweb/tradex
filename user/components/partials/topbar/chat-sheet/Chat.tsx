'use client';

import { useEffect, useRef } from 'react';
import Link from 'next/link';
import { getTicketStatusText } from '@/helper/ticket/getTicketStatusText';
import { useQuery } from '@tanstack/react-query';
import { MoreVertical, Settings2 } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { Ticket } from '@/types/Ticket';
import { toAbsoluteUrl } from '@/lib/helpers';
import { getMessages } from '@/services/ticket.services';
import { Avatar, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuPortal,
  DropdownMenuSub,
  DropdownMenuSubContent,
  DropdownMenuSubTrigger,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
  SheetBody,
  SheetContent,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from '@/components/ui/sheet';
import { ContentLoader } from '@/components/common/content-loader';
import { DownLoadFile } from '@/app/(pages)/ticket/list/components/download-file';
import SendMessageBox from '@/app/(pages)/ticket/list/components/send-message-box';

interface Props {
  id: number | string;
  hideChat: () => void;
}

export function Chat({ id, hideChat }: Props) {
  const t = useTranslations('ticket');
  const ts = useTranslations('ticket.table');
  const containerRef = useRef<HTMLDivElement | null>(null);

  const { isPending, data: ticket } = useQuery<Ticket>({
    queryKey: ['chat', id],
    queryFn: () => getMessages(id),
  });
  useEffect(() => {
    scrollToTarget();
  }, [ticket?.messages, id]);

  const scrollToTarget = () => {
    return (
      containerRef.current &&
      containerRef.current.scrollIntoView({ behavior: 'smooth', block: 'end' })
    );
  };

  return (
    <SheetContent
      className={`p-0 gap-0 w-full md:w-[450px] sm:max-w-none sm:inset-5 sm:start-auto h-auto 
            rounded-lg [&_[data-slot=sheet-close]]:top-4.5 [&_[data-slot=sheet-close]]:end-5`}
    >
      <SheetHeader>
        <div className="flex items-center justify-between p-3 border-b border-border">
          <SheetTitle>
            <Button
              type="button"
              variant="ghost"
              className="flex items-center gap-x-2"
              onClick={hideChat}
            >
              <i className="ki-filled ki-arrow-right rtl:rotate-0 ltr:rotate-180"></i>
              {t('table.tickets')}
            </Button>
          </SheetTitle>
          <Link href={'/ticket/add'}>
            <Button
              asChild
              size="sm"
              variant="outline"
              className="ltr:mr-8 rtl:ml-8"
            >
              <span>
                <i className="ki-filled ki-plus"></i>
                <span>{t('register_ticket')}</span>
              </span>
            </Button>
          </Link>
        </div>
        <div className="border-b border-border p-3">
          <div className="flex items-center justify-between gap-2">
            <div className="flex items-center gap-2">
              <div className="w-12 h-12 flex flex-shrink-0 items-center justify-center">
                <img
                  src={toAbsoluteUrl('/media/avatars/chat.png')}
                  className="w-full h-full rounded-full"
                  alt="avatar"
                />
              </div>
              {ticket && (
                <div className="flex flex-col gap-1 grow">
                  <span className="line-clamp-1 max-w-64 text-sm">
                    {ticket?.title}
                  </span>
                  <span className="text-xs">
                    ({ticket?.department?.name ?? '--'})
                  </span>
                </div>
              )}
            </div>
            <div className="flex items-center gap-2">
              {ticket && (
                <Badge
                  variant={
                    ticket?.status === 'new'
                      ? 'success'
                      : ticket?.status === 'closed'
                        ? 'destructive'
                        : ticket?.status === 'pending'
                          ? 'warning'
                          : 'primary'
                  }
                  size="lg"
                  shape="circle"
                  appearance="outline"
                  className="shrink-0"
                >
                  <span className="flex shrink-0">
                    {getTicketStatusText(ticket?.status, ts)}
                  </span>
                </Badge>
              )}

              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="ghost" mode="icon" size="sm">
                    <MoreVertical className="size-4!" />
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                  className="w-44"
                  side="bottom"
                  align="center"
                >
                  <DropdownMenuSub>
                    <DropdownMenuSubTrigger>
                      <Settings2 />
                      <span>{t('title_ticket')}</span>
                    </DropdownMenuSubTrigger>
                    <DropdownMenuPortal>
                      <DropdownMenuSubContent className="w-44">
                        <p>{ticket?.title}</p>
                      </DropdownMenuSubContent>
                    </DropdownMenuPortal>
                  </DropdownMenuSub>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>
          </div>
        </div>
        <div className="flex justify-between border-b border-border py-1 px-5 shadow-xs text-xs text-muted-foreground">
          {t('table.id')}
          <span>#{ticket?.id}</span>
        </div>
      </SheetHeader>
      <SheetBody className="kt-scrollable-y-auto grow space-y-3.5 pb-10">
        {isPending ? (
          <ContentLoader className="mt-[30%]" />
        ) : (
          ticket?.messages.map((messageBox) =>
            messageBox.from === 'admin' ? (
              <div
                key={messageBox.id}
                className="flex items-end justify-end gap-3 px-5"
              >
                <div className="flex flex-col gap-1 max-w-[80%]">
                  <div className="bg-primary text-primary-foreground text-sm font-medium p-3 rounded-lg shadow-xs break-words">
                    <span>{messageBox.message}</span>
                    {messageBox.files.length ? (
                      <ul className="flex flex-col gap-1 mt-2">
                        {messageBox.files.map((file) => (
                          <li key={file.id}>
                            <DownLoadFile file={file} />
                          </li>
                        ))}
                      </ul>
                    ) : null}
                  </div>
                  <div className="flex items-center justify-end gap-1">
                    <span className="text-xs text-secondary-foreground">
                      {messageBox?.created_at}
                    </span>
                  </div>
                </div>
                <div className="relative">
                  <Avatar className="size-9">
                    <AvatarImage
                      src={toAbsoluteUrl(`${messageBox.user?.avatar}`)}
                      alt="avatar"
                    />
                  </Avatar>
                </div>
              </div>
            ) : (
              <div key={messageBox.id} className="flex items-end gap-3 px-5">
                <Avatar className="size-9">
                  <AvatarImage
                    src={toAbsoluteUrl(`${messageBox.user?.avatar}`)}
                    alt="avatar"
                  />
                </Avatar>
                <div className="flex flex-col gap-1 max-w-[80%]">
                  <div className="bg-accent/50 text-secondary-foreground text-sm font-medium p-3 rounded-lg shadow-xs break-words">
                    <span>{messageBox.message}</span>
                    {messageBox.files.length ? (
                      <ul className="flex flex-col gap-1 mt-2">
                        {messageBox.files.map((file) => (
                          <li key={file.id}>
                            <DownLoadFile file={file} />
                          </li>
                        ))}
                      </ul>
                    ) : null}
                  </div>
                  <span className="text-xs text-muted-foreground">
                    {messageBox?.created_at}
                  </span>
                </div>
              </div>
            ),
          )
        )}
        <div ref={containerRef} />
      </SheetBody>
      <SheetFooter
        className={`${ticket?.status === 'closed' ? 'hidden' : 'block'} p-0 sm:space-x-0`}
      >
        {ticket?.id && <SendMessageBox ticketId={ticket?.id} />}
      </SheetFooter>
    </SheetContent>
  );
}
