export interface HeartRateZone {
  id: number;
  zone_number: number;
  name: string;
  min_bpm: number;
  max_bpm: number;
  color: string | null;
}
