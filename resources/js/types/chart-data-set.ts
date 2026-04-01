import type { ChartDataPoint } from "./chart-data-point";
import { Zone } from "./zone";

export interface ChartDataSet {
  label: string;
  samples: ChartDataPoint[];
  metaData: {
    yMin: number | null;
    yMax: number | null;
    yMinLabel: number | string | null;
    yMaxLabel: number | string | null;
  },
  reverse: boolean;
  zones?: Zone[];
  lineColor?: string;
}
