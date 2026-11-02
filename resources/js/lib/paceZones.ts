import { ColorConfig } from "@/types";
import { PaceZone } from "@/types/pace-zone";
import { zoneColors } from "./zoneColors";

export const getZoneForPace = (pace: number, zones: PaceZone[]): PaceZone | null => {
  if (!zones.length) return null;
  
  const sorted = [...zones].sort((a, b) => a.minSeconds - b.maxSeconds);

  if (pace < sorted[sorted.length - 1].minSeconds ) {
    return sorted[0]
  }; // Faster than any zone.

  if (pace > sorted[0].maxSeconds) {
    return sorted[sorted.length - 1]
  }; // Slower than any zone.

  return sorted.find(z => pace >= z.minSeconds && pace <= z.maxSeconds) ?? null;
}

export const paceZoneColors = (zone: PaceZone): ColorConfig | null => {
  return zoneColors[zone.zoneNumber] ?? null;
}
