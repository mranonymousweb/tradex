export const getCompact = (number: number) => {
  if (isNaN(number)) return 0;

  const formatter = new Intl.NumberFormat('en', {
    notation: 'compact',
  });
  return formatter.format(number);
};
