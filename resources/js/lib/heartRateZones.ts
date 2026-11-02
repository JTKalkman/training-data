import { ColorConfig, HeartRateZone } from "@/types";

const heartRateZoneColorMap: Record<string, ColorConfig> = {
  blue: {
    hex: {
      foreground_color: '#74d4ff',
      background_color: '#dff2fe'
    },
    tailwind: {
      foreground_color: 'bg-sky-300',
      background_color: 'bg-sky-100'
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
      background_color: '#ffb86a'
    },
    tailwind: {
      foreground_color: 'bg-orange-500',
      background_color: 'bg-orange-300'
    }
  },
  red: {
    hex: {
      foreground_color: '#fb2c36',
      background_color: '#ffa2a2'
    },
    tailwind: {
      foreground_color: 'bg-red-500',
      background_color: 'bg-red-300'
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
