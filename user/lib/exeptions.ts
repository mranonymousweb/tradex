const ValidationsError = (res: Response, data: any) => {
  if (res.status === 422) {
    return Object.entries(data?.error?.details);
  }
  if (res.status === 400) {
    return Object.entries({ message: data?.message });
  }
  throw new Error(data?.message || 'Something went wrong !!');
};
export default ValidationsError;
