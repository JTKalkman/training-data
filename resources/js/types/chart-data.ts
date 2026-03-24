import type { ChartDataSet } from "./chart-data-set";

export interface ChartData {
  xAxis: string[];
  datasets: Record<string, ChartDataSet>;
}
