'use client';

import { Fragment, useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { PaginationState } from '@tanstack/react-table';
import { getLinks } from '@/services/marketing.services';
import { CodeMarketing } from './code-marketig';
import { Links } from './links-table';

const MarketingContent = () => {
  const [pagination, setPagination] = useState<PaginationState>({
    pageIndex: 0,
    pageSize: 12,
  });
  const { data, isPending } = useQuery({
    queryKey: ['links-marketing', pagination.pageIndex],
    queryFn: () => getLinks(pagination.pageIndex + 1),
  });

  return (
    <Fragment>
      <CodeMarketing income={data?.parent_amount} />
      <Links
        data={data}
        isPending={isPending}
        pagination={pagination}
        setPagination={setPagination}
      />
    </Fragment>
  );
};

export { MarketingContent };
