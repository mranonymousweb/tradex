/**
 * Reformat jalali date picker value into YYYY-MM_DD
 *
 * @param date Date object from jalali date picker (has .unix method)
 * @returns string formatted as YYY-MM-DD
 */
export const reformatDate = (date: any): string => {
  if (!date) return '';
  const timestamp = date.unix;
  const dateTime = new Date(timestamp * 1000);

  if (isNaN(dateTime.getTime())) return '';

  const year = dateTime.getFullYear().toString();
  const month = (dateTime.getMonth() + 1).toString().padStart(2, '0');
  const day = dateTime.getDate().toString().padStart(2, '0');

  return `${year}-${month}-${day}`;
};
