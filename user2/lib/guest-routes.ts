/** Routes accessible without login (view-only). */
export const GUEST_ACCESSIBLE_PATHS = ['/markets', '/trade/spot'] as const;

export function isGuestAccessiblePath(pathname: string): boolean {
  return GUEST_ACCESSIBLE_PATHS.some(
    (path) => pathname === path || pathname.startsWith(`${path}/`),
  );
}
