export function flattenErrorObject(
  obj: Record<string, string[]> | undefined,
): string[] {
  if (!obj) return [];
  return Object.values(obj).flat();
}
