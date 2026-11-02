export function formatDate(isoString: string): string {
  const date = new Date(isoString);

  const year = date.getUTCFullYear();
  const month = String(date.getUTCMonth() + 1).padStart(2, '0');
  const day = String(date.getUTCDate()).padStart(2, '0');

  const hours = String(date.getUTCHours()).padStart(2, '0');
  const minutes = String(date.getUTCMinutes()).padStart(2, '0');

  return `${year}/${month}/${day} - ${hours}:${minutes}`;
}

export function formatOnlyDate(isoString: string): string {
  const date = new Date(isoString);

  const year = date.getUTCFullYear();
  const month = String(date.getUTCMonth() + 1).padStart(2, '0');
  const day = String(date.getUTCDate()).padStart(2, '0');
  return `${year}/${month}/${day}`;
}

export function time(isoString: string | number) {
  const date = new Date(isoString);

  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  const scond = String(date.getSeconds()).padStart(2, '0');

  return `${hours}:${minutes}:${scond}`;
}

export function localeDate(isoString: string, lang: string = 'en') {
  const date = new Date(isoString);
  const locales = lang === 'fa' ? 'fa-IR' : 'en-US';
  return date.toLocaleString(locales);
}
