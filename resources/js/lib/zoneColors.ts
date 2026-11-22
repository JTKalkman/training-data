import { ColorConfig } from "@/types";

export const zoneColors: Record<number, ColorConfig> = {
  1: {
    hex: {
      foreground_color: '#737373',
      background_color: '#f5f5f5'
    },
    tailwind: {
      foreground_color: 'bg-neutral-500',
      background_color: 'bg-neutral-100'
    }
  },
  2: {
    hex: {
      foreground_color: '#0084d1',
      background_color: '#b8e6fe'
    },
    tailwind: {
      foreground_color: 'bg-sky-500',
      background_color: 'bg-sky-200'
    }
  },
  3: {
    hex: {
      foreground_color: '#00a63e',
      background_color: '#b9f8cf'
    },
    tailwind: {
      foreground_color: 'bg-green-600',
      background_color: 'bg-green-200'
    }
  },
  4: {
    hex: {
      foreground_color: '#f0b100',
      background_color: '#fef9c2'
    },
    tailwind: {
      foreground_color: 'bg-yellow-500',
      background_color: 'bg-yellow-100'
    }
  },
  5: {
    hex: {
      foreground_color: '#e7000b',
      background_color: '#ffc9c9'
    },
    tailwind: {
      foreground_color: 'bg-red-600',
      background_color: 'bg-red-200'
    }
  },
};
