import { ColorConfig, HeartRateZone } from "@/types";
import { zoneColors } from "./zoneColors";

export const getZoneForHeartRate = (bpm: number, zones: HeartRateZone[]): HeartRateZone | null => {
  if (!zones.length) return null;

  const sorted = [...zones].sort((a, b) => a.min_bpm - b.max_bpm);

  if (bpm < sorted[0].min_bpm) return sorted[0]; // Below any zone.
  if (bpm > sorted[sorted.length - 1].max_bpm) return sorted[sorted.length - 1]; // Above all zones.

  return sorted.find(z => bpm >= z.min_bpm && bpm <= z.max_bpm) ?? null;
}

export const heartRateZoneColors = (zoneNumber: number): ColorConfig | null => {
  return zoneColors[zoneNumber] ?? null;
}
