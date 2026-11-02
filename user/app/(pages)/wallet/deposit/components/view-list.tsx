/* eslint-disable react-hooks/exhaustive-deps */

import { memo, useEffect, useRef } from 'react';

const ViewList = ({
  hasNextPage,
  isFetchingNextPage,
  fetchNextPage,
  setDropDown,
  children,
  contentDropRef,
}: {
  hasNextPage: boolean;
  isFetchingNextPage: boolean;
  fetchNextPage: () => void;
  children: React.ReactNode;
  setDropDown: (d: boolean) => void;
  contentDropRef: React.RefObject<HTMLDivElement | null>;
}) => {
  const viewPortRef = useRef<HTMLDivElement | null>(null);

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

    const hideDropdown = function (event: MouseEvent) {
      const content = contentDropRef.current;
      if (
        content &&
        event.target instanceof Node &&
        !content.contains(event.target)
      ) {
        setDropDown(false);
      }
    };

    vp.addEventListener('scroll', handler, { passive: true });
    window.addEventListener('click', hideDropdown);
    return () => {
      vp.removeEventListener('scroll', handler);
      window.removeEventListener('click', hideDropdown);
    };
  }, [hasNextPage, isFetchingNextPage, fetchNextPage]);

  return (
    <div ref={viewPortRef} className="overflow-y-auto max-h-60 px-3 pt-2">
      {children}
    </div>
  );
};

export default memo(ViewList);
