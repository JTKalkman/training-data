import { ColorConfig, HeartRateZone } from "@/types";
import { PaceZone } from "@/types/pace-zone";

const paceZoneColorMap: Record<number, ColorConfig> = {
  1: {
    hex: {
      foreground_color: '#74d4ff',
      background_color: '#dff2fe'
    },
    tailwind: {
      foreground_color: 'bg-sky-300',
      background_color: 'bg-sky-100'
    }
  },
  2: {
    hex: {
      foreground_color: '#00c950',
      background_color: '#b9f8cf'
    },
    tailwind: {
      foreground_color: 'bg-green-500',
      background_color: 'bg-green-200'
    }
  },
  3: {
    hex: {
      foreground_color: '#f0b100',
      background_color: '#fff085'
    },
    tailwind: {
      foreground_color: 'bg-yellow-500',
      background_color: 'bg-yellow-200'
    }
  },
  4: {
    hex: {
      foreground_color: '#ff6900',
      background_color: '#ffd6a7'
    },
    tailwind: {
      foreground_color: 'bg-orange-500',
      background_color: 'bg-orange-200'
    }
  },
  5: {
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
  return paceZoneColorMap[zone.zoneNumber] ?? null;
}
