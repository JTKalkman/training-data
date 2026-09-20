import { ColorConfig, HeartRateZone } from "@/types";

const heartRateZoneColorMap: Record<string, ColorConfig> = {
  blue: {
    hex: {
      foreground_color: '#00a6f4',
      background_color: '#b8e6fe'
    },
    tailwind: {
      foreground_color: 'bg-sky-500',
      background_color: 'bg-sky-200'
    }
  },
  green: {
    hex: {
      foreground_color: '#00c950',
      background_color: '#b9f8cf'
    },
    tailwind: {
      foreground_color: 'bg-green-500',
      background_color: 'bg-green-200'
    }
  },
  yellow: {
    hex: {
      foreground_color: '#f0b100',
      background_color: '#fff085'
    },
    tailwind: {
      foreground_color: 'bg-yellow-500',
      background_color: 'bg-yellow-200'
    }
  },
  orange: {
    hex: {
      foreground_color: '#ff6900',
      background_color: '#ffd6a7'
    },
    tailwind: {
      foreground_color: 'bg-orange-500',
      background_color: 'bg-orange-200'
    }
  },
  red: {
    hex: {
      foreground_color: '#fb2c36',
      background_color: '#ffc9c9'
    },
    tailwind: {
      foreground_color: 'bg-red-500',
      background_color: 'bg-red-200'
    }
  },
};

export const getZoneForHeartRate = (bpm: number, zones: HeartRateZone[]): HeartRateZone | null => {
  if (!zones.length) return null;

  const sorted = [...zones].sort((a, b) => a.min_bpm - b.max_bpm);

  if (bpm < sorted[0].min_bpm) return sorted[0]; // Below any zone.
  if (bpm > sorted[sorted.length - 1].max_bpm) return sorted[sorted.length - 1]; // Above all zones.

  return sorted.find(z => bpm >= z.min_bpm && bpm <= z.max_bpm) ?? null;
}

export const heartRateZoneColors = (color: string): ColorConfig | null => {
  return heartRateZoneColorMap[color] ?? null;
}
