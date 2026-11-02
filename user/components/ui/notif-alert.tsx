import { PopupNotif } from '@/helper/notification/popup-notif';
import { TapeNotif } from '@/helper/notification/tape-notif';
import { Notification } from '@/types/notification';

export const NotifAlert = ({
  popup_notif,
  notifTape,
}: {
  popup_notif: Notification[];
  notifTape: Notification[];
}) => {
  const has_popup_notif = popup_notif?.find(
    (notif) => notif.section === 'pop_up',
  );

  return (
    <>
      {notifTape?.map((tape) => (
        <TapeNotif key={tape.id} tape={tape} />
      ))}
      {has_popup_notif && <PopupNotif notif={has_popup_notif} />}
    </>
  );
};
