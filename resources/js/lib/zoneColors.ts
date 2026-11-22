import { ColorConfig } from "@/types";

export const zoneColors: Record<number, ColorConfig> = {
  1: {
    hex: {
      foreground_color: '#737373',
      background_color: '#e5e5e5'
    },
    tailwind: {
      foreground_color: 'bg-neutral-500',
      background_color: 'bg-neutral-300'
    }
  },
  2: {
    hex: {
      foreground_color: '#0084d1',
      background_color: '#74d4ff'
    },
    tailwind: {
      foreground_color: 'bg-sky-500',
      background_color: 'bg-sky-300'
    }
  },
  3: {
    hex: {
      foreground_color: '#00a63e',
      background_color: '#7bf1a8'
    },
    tailwind: {
      foreground_color: 'bg-green-600',
      background_color: 'bg-green-300'
    }
  },
  4: {
    hex: {
      foreground_color: '#f0b100',
      background_color: '#fff085'
    },
    tailwind: {
      foreground_color: 'bg-yellow-500',
      background_color: 'bg-yellow-200'
    }
  },
  5: {
    hex: {
      foreground_color: '#e7000b',
      background_color: '#ffa2a2'
    },
    tailwind: {
      foreground_color: 'bg-red-600',
      background_color: 'bg-red-300'
    }
  },
};
