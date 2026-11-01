import { PaceZone } from "./pace-zone";

export interface PaceZoneSet {
  id: number;
  masPace: string;
  masSecondsPerKm: number;
  scheme: string;
  source: string;
  zones: PaceZone[]
};
