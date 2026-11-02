import { ChallengeResponse } from '@/types/challenge';

export function properties(data: ChallengeResponse) {
  const challenge_duration = data.properties.map((pro) => {
    return { step: pro.step, item: pro.challenge_duration };
  });
  const min_day = data.properties.map((pro) => {
    return { step: pro.step, item: pro.min_day };
  });
  const profit_target = data.properties.map((pro) => {
    return { step: pro.step, item: pro.profit_target };
  });
  const daily_drawdown = data.properties.map((pro) => {
    return { step: pro.step, item: pro.daily_drawdown };
  });
  const max_drawdown = data.properties.map((pro) => {
    return { step: pro.step, item: pro.max_drawdown };
  });
  const risk_reset_time = data.properties.map((pro) => {
    return { step: pro.step, item: pro.risk_reset_time };
  });
  const max_open_trades = data.properties.map((pro) => {
    return { step: pro.step, item: pro.max_open_trades };
  });
  const max_position_size = data.properties.map((pro) => {
    return { step: pro.step, item: pro.max_position_size };
  });

  const reuslt = [
    { id: 1, title: 'تعداد مرحله', info: data.properties.length },
    { id: 2, title: 'مدت زمان چالش (روز)', info: challenge_duration },
    { id: 3, title: 'حداقل روزهای فعال معاملاتی', info: min_day },
    { id: 4, title: 'تارگت سود (درصد)', info: profit_target },
    { id: 5, title: 'افت سرمایه روزانه (درصد)', info: daily_drawdown },
    { id: 6, title: 'حداکثر افت سرمایه (درصد)', info: max_drawdown },
    { id: 7, title: 'زمان بازنشانی ریسک', info: risk_reset_time },
    { id: 9, title: 'اهرم', info: 'براساس بایننس' },
    { id: 10, title: 'حداکثر معاملات باز', info: max_open_trades },
    { id: 11, title: 'حداکثر اندازه موقعیت', info: max_position_size },
    { id: 12, title: 'TP/SL', info: 'دارد' },
  ];
  return reuslt;
}
