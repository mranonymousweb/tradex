'use client';

import { useMemo } from 'react';

interface Props {
    value: string;
    setValue: (value: string) => void;
    maxLeverage: number;
}

const TOOLTIP_OFFSET = 12;

const formatTickLabel = (tick: number) =>
    `${Number.isInteger(tick) ? tick : Math.round(tick)}X`;

const getTrackPercent = (val: number, min: number, max: number) => {
    if (max <= min) return 0;
    return ((val - min) / (max - min)) * 100;
};

const getTickPositionClass = (index: number, total: number) => {
    if (index === 0) return 'translate-x-0';
    if (index === total - 1) return '-translate-x-full';
    return '-translate-x-1/2';
};

export const LeverageSlider = ({ value, setValue, maxLeverage }: Props) => {
    const safeMax = Math.max(1, maxLeverage);
    const min = 1;
    const numericValue = Math.min(Math.max(Number(value) || min, min), safeMax);

    const ticks = useMemo(
        () => [
            min,
            safeMax / 5,
            (safeMax * 2) / 5,
            (safeMax * 3) / 5,
            (safeMax * 4) / 5,
            safeMax,
        ],
        [safeMax],
    );

    const slide = getTrackPercent(numericValue, min, safeMax);
    const position = slide > 50 ? slide - 2 : slide;

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setValue(e.target.value);
    };

    const isTickActive = (tick: number) => numericValue >= tick;

    return (
        <div className="silder-range-input relative">
            <span
                className={`
                    tooltip-slider absolute w-8 h-5 bg-mono text-background 
                    justify-center items-center text-xs rounded-sm -top-10`
                }
                style={
                    {
                        '--rangeTooltip': `calc(${position}% - ${TOOLTIP_OFFSET}px)`,
                    } as React.CSSProperties
                }
            >
                {value}X
            </span>
            <input
                type="range"
                min={min}
                max={safeMax}
                value={value}
                onChange={handleChange}
                className="w-full h-1 inset-0 absolute z-[2] mt-[-2px]"
            />
            <ul
                className="relative h-px bg-muted"
                style={{ '--width': `${slide}%` } as React.CSSProperties}
            >
                {ticks.map((tick, index) => (
                    <li
                        key={index}
                        className={`absolute top-1/2 -translate-y-1/2 w-1.5 h-1.5 rotate-45 border ${getTickPositionClass(index, ticks.length)} ${
                            isTickActive(tick)
                                ? 'bg-mono border-mono'
                                : 'bg-background'
                        }`}
                        style={{ left: `${getTrackPercent(tick, min, safeMax)}%` }}
                    />
                ))}
            </ul>
            <ul className="relative mt-3 h-4 text-xs text-muted-foreground">
                {ticks.map((tick, index) => (
                    <li
                        key={index}
                        className={`absolute whitespace-nowrap ${getTickPositionClass(index, ticks.length)}`}
                        style={{ left: `${getTrackPercent(tick, min, safeMax)}%` }}
                    >
                        {formatTickLabel(tick)}
                    </li>
                ))}
            </ul>
        </div>
    );
};
