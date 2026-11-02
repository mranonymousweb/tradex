import { MenuConfig, MenuItem } from '@/config/types';

export const normalizeMenuPath = (path: string): string => path.split('?')[0];

export const isMenuPathActive = (
  pathname: string,
  path: string | undefined,
): boolean => {
  if (!path) return false;

  const normalizedPath = normalizeMenuPath(path);

  if (normalizedPath === '/') {
    return normalizedPath === pathname;
  }

  return pathname.startsWith(normalizedPath);
};

export const getCurrentMenuItem = (
  pathname: string,
  items: MenuConfig,
): MenuItem | undefined => {
  for (const item of items) {
    if (item.path && isMenuPathActive(pathname, item.path)) {
      if (item.children && item.children.length > 0) {
        const childMatch = getCurrentMenuItem(pathname, item.children);
        return childMatch || item;
      }
      return item;
    }

    if (item.children && item.children.length > 0) {
      const childMatch = getCurrentMenuItem(pathname, item.children);
      if (childMatch) {
        return childMatch;
      }
    }
  }

  return undefined;
};

export const getMenuBreadcrumb = (
  pathname: string,
  items: MenuConfig,
): MenuItem[] => {
  const findBreadcrumb = (
    nodes: MenuItem[],
    breadcrumb: MenuItem[] = [],
  ): MenuItem[] => {
    for (const item of nodes) {
      const currentBreadcrumb = [...breadcrumb, item];

      if (item.path && isMenuPathActive(pathname, item.path)) {
        return currentBreadcrumb;
      }

      if (item.children && item.children.length > 0) {
        const childBreadcrumb = findBreadcrumb(item.children, currentBreadcrumb);
        if (childBreadcrumb.length > currentBreadcrumb.length) {
          return childBreadcrumb;
        }
      }
    }

    return breadcrumb;
  };

  const breadcrumb = findBreadcrumb(items);
  return breadcrumb.length > 0 ? breadcrumb : [];
};
