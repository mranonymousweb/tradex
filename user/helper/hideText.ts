export const hideText = (value: string | undefined, s = 4, e = 7) => {
  if (!value) return;
  const selectText = value.slice(0, 4);
  const selectTextEnd = value.slice(-4);
  const hideText = selectText + `******` + selectTextEnd;
  return hideText;
};
