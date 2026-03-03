export interface SampleDataPoint {
  time: number;
  time_label: string;
  heart_rate: number;
  speed?: number;
  cadence?: number;
  altitude?: number;
  distance?: number;
  pace?: number;
};
