import Image from 'next/image';
import { useQuery } from '@tanstack/react-query';
import { getProfileInfo } from '@/services/profile.services';
import { UserDropdownMenu } from './user-dropdown-menu';

//import { ContentLoader } from "@/components/common/content-loader";

export const WrapperUserDropdownMenu = () => {
  const { data: user, isPending } = useQuery({
    queryKey: ['profile'],
    queryFn: () => getProfileInfo(),
  });
  if (isPending)
    return (
      <div className="size-5 md:size-7 bg-secondary/50 rounded-full border-2 border-muted shrink-0"></div>
    );

  return (
    user && (
      <UserDropdownMenu
        user={user}
        trigger={
          <Image
            width={28}
            height={28}
            className="size-10 md:size-7 rounded-full border-2 border-muted shrink-0 cursor-pointer object-cover"
            src={user?.avatar}
            alt="User Avatar"
          />
        }
      />
    )
  );
};
