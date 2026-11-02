import { memo, useEffect, useRef } from 'react';

const ViewList = ({
  hasNextPage,
  isFetchingNextPage,
  fetchNextPage,
  children,
}: {
  hasNextPage: boolean;
  isFetchingNextPage: boolean;
  fetchNextPage: () => void;
  children: React.ReactNode;
}) => {
  const viewPortRef = useRef<HTMLUListElement | null>(null);

  useEffect(() => {
    const vp = viewPortRef.current;
    if (!vp) return;

    const handler = () => {
      const { scrollTop, clientHeight, scrollHeight } = vp;
      const isBottom = scrollTop + clientHeight >= scrollHeight - 20;

      if (isBottom && hasNextPage && !isFetchingNextPage) {
        fetchNextPage();
      }
    };

    vp.addEventListener('scroll', handler, { passive: true });
    return () => vp.removeEventListener('scroll', handler);
  }, [hasNextPage, isFetchingNextPage, fetchNextPage]);

  return (
    <ul ref={viewPortRef} className="h-96 overflow-y-auto">
      {children}
    </ul>
  );
};

export default memo(ViewList);
