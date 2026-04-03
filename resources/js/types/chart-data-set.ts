import type { ChartDataPoint } from "./chart-data-point";

export interface ChartDataSet {
  label: string;
  samples: ChartDataPoint[];
  metaData: {
    min: number | null;
    max: number | null;
    minStr: string | number | null;
    maxStr: string | number | null;
  },
  reverse: boolean;
}
