import type { ChartDataPoint } from "./chart-data-point";

export interface ChartDataSet {
  label: string;
  samples: ChartDataPoint[];
  metaData: {
    min: string| number | null;
    max: string| number | null;
  },
  reverse: boolean;
}
