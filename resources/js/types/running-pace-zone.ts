export interface RunningPaceZone {
  zoneNumber: number;
  name: string;
  minSeconds: number;
  maxSeconds: number;
  color: string|null;
  inZoneSeconds: number|null;
}
