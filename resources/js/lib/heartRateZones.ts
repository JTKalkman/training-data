import { HeartRateZone } from "@/types";

export function getZoneForHeartRate(bpm: number, zones: HeartRateZone[]): HeartRateZone | null {
  return zones.find(z => bpm >= z.min_bpm && bpm <= z.max_bpm) ?? null;
}

const zoneColorClassesMap: Record<string, string> = {
  blue: 'bg-blue-500',
  green: 'bg-green-500',
  yellow: 'bg-yellow-500',
  orange: 'bg-orange-500',
  red: 'bg-red-500',
};

export const zoneColorClasses = (color: string): string | null => {
  return zoneColorClassesMap[color] ?? null;
}
