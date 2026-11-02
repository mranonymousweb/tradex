const isBrowser = typeof window !== 'undefined';

const getData = (key: string): unknown | undefined => {
  if (!isBrowser) return;

  try {
    const data = localStorage.getItem(key);
    if (data) {
      return JSON.parse(data);
    }
  } catch (error) {
    console.error('Read from local storage', error);
  }
};

const setData = (key: string, value: unknown): void => {
  if (!isBrowser) return;

  try {
    localStorage.setItem(key, JSON.stringify(value));
  } catch (error) {
    console.error('Save in local storage', error);
  }
};

export { getData, setData };
