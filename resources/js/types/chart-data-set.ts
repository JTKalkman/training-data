import type { ChartDataPoint } from "./chart-data-point";
import { ColorZone } from "./color-zone";

export interface ChartDataSet {
  label: string;
  samples: ChartDataPoint[];
  metaData: {
    min: number | null;
    max: number | null;
    minStr: string | number | null;
    maxStr: string | number | null;
    zones: ColorZone[]
  },
  reverse: boolean;
}
