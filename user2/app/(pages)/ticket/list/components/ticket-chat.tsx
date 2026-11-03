'use client';

import React from 'react';
import { Sheet } from '@/components/ui/sheet';
import { Chat } from '@/components/partials/topbar/chat-sheet/Chat';

interface Props {
  id: string | number;
  closeChat: () => void;
}

export const TicketChat: React.FC<Props> = ({ id, closeChat }) => {
  return (
    <Sheet open={true} onOpenChange={closeChat}>
      <Chat key={id} id={id} hideChat={closeChat} />
    </Sheet>
  );
};
