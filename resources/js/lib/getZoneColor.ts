import { ColorConfig, ColorZone } from "@/types";

export function getZoneColor(value: number | null, zones: ColorZone[], fallbackColors: ColorConfig): ColorConfig {
  if (!zones.length) return fallbackColors;

  const sorted = [...zones].sort((a, b) => a.min - b.min);

  if (value === null) return sorted[0].colorConfig ?? fallbackColors; // Missing values.

  if (value < sorted[0].min) return sorted[0].colorConfig ?? fallbackColors; // Below any zone.
  if (value > sorted[sorted.length - 1].max) return sorted[sorted.length - 1].colorConfig ?? fallbackColors; // Above all zones.

  return sorted.find(z => value >= z.min && value <= z.max)?.colorConfig ?? fallbackColors;
}
