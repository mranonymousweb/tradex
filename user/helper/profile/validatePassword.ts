export const validatePassword = (password: string, minLength = 8): string[] => {
  const errors: string[] = [];

  if (password.length < minLength) {
    errors.push(`رمز عبور باید حداقل ${minLength} کاراکتر باشد.`);
  }
  if (!/[A-Z]/.test(password)) {
    errors.push('رمز عبور باید شامل یک حرف بزرگ باشد.');
  }
  if (!/[a-z]/.test(password)) {
    errors.push('رمز عبور باید شامل یک حرف کوچک باشد.');
  }
  if (!/\d/.test(password)) {
    errors.push('رمز عبور باید شامل یک عدد باشد.');
  }
  if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
    errors.push('رمز عبور باید حداقل شامل یک کاراکتر خاص باشد.');
  }

  return errors;
};
