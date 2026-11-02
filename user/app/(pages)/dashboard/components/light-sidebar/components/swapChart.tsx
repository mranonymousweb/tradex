'use client';

import dynamic from 'next/dynamic';
import { getCompact } from '@/helper/compact';
import { ApexOptions } from 'apexcharts';
import { useTranslations } from 'next-intl';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const ApexChart = dynamic(() => import('react-apexcharts'), { ssr: false });

// Hardcoded dummy data for the earnings chart
export interface ChartItem {
  date: string; // "YYYY-MM-DD"
  day: number;
  total: number;
}

export interface ChartResponse {
  max_total: number;
  chart: ChartItem[];
}

const SwapChart = ({ chartsData }: { chartsData: ChartResponse }) => {
  const t = useTranslations('dashboard');
  const categories = chartsData?.chart?.map((ch) => {
    return ch.day;
  });
  const chartData = chartsData?.chart?.map((ch) => {
    return ch.total;
  });

  const options: ApexOptions = {
    series: [
      {
        name: 'گزارش عمکلرد سفارش فوری',
        data: chartData ?? [],
      },
    ],
    chart: {
      height: 250,
      type: 'area',
      toolbar: {
        show: false,
      },
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    stroke: {
      curve: 'smooth',
      show: true,
      width: 3,
      colors: ['var(--color-primary)'],
    },
    xaxis: {
      categories: categories,
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
      labels: {
        style: {
          colors: 'var(--color-secondary-foreground)',
          fontSize: '12px',
        },
      },
      crosshairs: {
        position: 'front',
        stroke: {
          color: 'var(--color-primary)',
          width: 1,
          dashArray: 3,
        },
      },
      tooltip: {
        enabled: false,
        formatter: undefined,
        offsetY: 0,
        style: {
          fontSize: '12px',
        },
      },
    },
    yaxis: {
      min: 0,
      max: chartsData?.max_total ?? 0,
      tickAmount: 5,
      axisTicks: {
        show: false,
      },
      labels: {
        style: {
          colors: 'var(--color-secondary-foreground)',
          fontSize: '12px',
        },
        formatter: (defaultValue) => {
          return `${getCompact(defaultValue)}`;
        },
      },
    },
    tooltip: {
      enabled: false,
      custom({ series, seriesIndex, dataPointIndex }) {
        const number = parseInt(series[seriesIndex][dataPointIndex]) * 1000;

        const formatter = new Intl.NumberFormat('en-US', {
          style: 'currency',
          currency: 'USD',
        });

        const formattedNumber = formatter.format(number);

        return `
          <div class="flex flex-col gap-2 p-3.5">
            <div class="flex items-center gap-1.5">
              <div class="font-semibold text-base text-mono">${formattedNumber}</div>
            </div>
          </div>
          `;
      },
    },
    markers: {
      size: 0,
      colors: 'var(--color-white)',
      strokeColors: 'var(--color-primary)',
      strokeWidth: 4,
      strokeOpacity: 1,
      strokeDashArray: 0,
      fillOpacity: 1,
      discrete: [],
      shape: 'circle',
      offsetX: 0,
      offsetY: 0,
      onClick: undefined,
      onDblClick: undefined,
      showNullDataPoints: true,
      hover: {
        size: 8,
        sizeOffset: 0,
      },
    },
    fill: {
      gradient: {
        opacityFrom: 0.25,
        opacityTo: 0,
      },
    },
    grid: {
      borderColor: 'var(--color-border)',
      strokeDashArray: 5,
      yaxis: {
        lines: {
          show: true,
        },
      },
      xaxis: {
        lines: {
          show: false,
        },
      },
    },
  };

  return (
    <Card className="h-full">
      <CardHeader className="py-2 lg:py-0">
        <CardTitle className="text-sm xl:text-base">
          {t('title_chart')}
        </CardTitle>
        <div className="flex items-center gap-1 text-sm">
          <i className="ki-outline ki-calendar text-lg"></i>
          <span>{t('current_month')}</span>
        </div>
      </CardHeader>
      <CardContent className="flex flex-col justify-end items-stretch grow px-3 py-1">
        <ApexChart
          id="earnings_chart"
          options={options}
          series={options.series}
          type="area"
          max-width="694"
          height="250"
        />
      </CardContent>
    </Card>
  );
};

export { SwapChart };
