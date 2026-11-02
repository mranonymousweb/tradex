'use client';

interface Props {
  value: string;
  setValue: (value: string) => void;
  maxLeverage: number;
}

export const LeverageSlider = ({ value, setValue, maxLeverage }: Props) => {
  const slide = Math.floor((+value / maxLeverage) * 100);
  const position = +slide > slide / 2 ? +slide - 2 : +slide;
  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setValue(e.target.value);
  };

  const step1 = maxLeverage / 5;
  const step2 = step1 * 2;
  const step3 = step1 * 3;
  const step4 = step1 * 4;

  return (
    <div className="silder-range-input relative">
      <span
        className={`
                    tooltip-slider absolute w-8 h-5 bg-mono text-background 
                    justify-center items-center text-xs rounded-sm -top-10`}
        style={
          {
            '--rangeTooltip': `calc(${position}% - 9px)`,
          } as React.CSSProperties
        }
      >
        {value}X
      </span>
      <input
        type="range"
        min={1}
        max={maxLeverage}
        value={value}
        onChange={handleChange}
        className="w-full h-1 inset-0 absolute z-[2] mt-[-2px]"
      />
      <ul
        className="flex items-center justify-between relative h-px bg-muted"
        style={{ '--width': slide + '%' } as React.CSSProperties}
      >
        <li className={`w-1.5 h-1.5 rotate-45 bg-mono border border-mono`}></li>
        <li
          className={`w-1.5 h-1.5 rotate-45 bg-background border ${Number(value) > step1 && 'bg-mono border-mono'}`}
        ></li>
        <li
          className={`w-1.5 h-1.5 rotate-45 bg-background border ${Number(value) > step2 && 'bg-mono border-mono'}`}
        ></li>
        <li
          className={`w-1.5 h-1.5 rotate-45 bg-background border ${Number(value) > step3 && 'bg-mono border-mono'}`}
        ></li>
        <li
          className={`w-1.5 h-1.5 rotate-45 bg-background border ${Number(value) > step4 && 'bg-mono border-mono'}`}
        ></li>
        <li
          className={`w-1.5 h-1.5 rotate-45 bg-background border ${Number(value) > maxLeverage && 'bg-mono border-mono'}`}
        ></li>
      </ul>
      <ul className="flex items-center justify-between text-xs text-muted-foreground mt-3">
        <li>1X</li>
        <li className="ml-2.5">{step1}X</li>
        <li className="ml-2.5">{step2}X</li>
        <li className="ml-2.5">{step3}X</li>
        <li className="-mr-2">{step4}X</li>
        <li>{maxLeverage}X</li>
      </ul>
    </div>
  );
};
