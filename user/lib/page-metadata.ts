import { Metadata } from 'next';
import { getTranslations } from 'next-intl/server';
import { MENU_SIDEBAR } from '@/config/general.config';
import { getCurrentMenuItem } from '@/lib/menu';
import { metaData } from '@/services/metaData.services';

type CreatePageMetadataOptions = {
  title?: string;
};

export async function createPageMetadata(
  pathname: string,
  options?: CreatePageMetadataOptions,
): Promise<Metadata> {
  const [meta, tSidebar] = await Promise.all([
    metaData(),
    getTranslations('sidebar'),
  ]);

  const menuItem = getCurrentMenuItem(
    pathname,
    MENU_SIDEBAR(meta, (key) => tSidebar(key)),
  );

  const title = options?.title ?? menuItem?.title;

  return title ? { title } : {};
}
