export const getDeviceType = () => {
  const ua = navigator.userAgent;

  if (/mobile/i.test(ua)) return 'Mobile';
  if (/tablet/i.test(ua)) return 'Tablet';
  return 'Desktop';
};

export const getBrowser = () => {
  const userAgent = navigator.userAgent;
  let browserName;

  if (
    userAgent.includes('Chrome') &&
    !userAgent.includes('Edge') &&
    !userAgent.includes('OPR')
  ) {
    browserName = 'Chrome';
  } else if (userAgent.includes('Safari') && !userAgent.includes('Chrome')) {
    browserName = 'Safari';
  } else if (userAgent.includes('Firefox')) {
    browserName = 'Firefox';
  } else if (userAgent.includes('Edg')) {
    browserName = 'Edge';
  } else if (userAgent.includes('OPR') || userAgent.includes('Opera')) {
    browserName = 'Opera';
  } else {
    browserName = 'Unknown';
  }
  return browserName;
};
