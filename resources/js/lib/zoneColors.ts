import { ColorConfig } from "@/types";

export const zoneColors: Record<number, ColorConfig> = {
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
      background_color: '#ffb86a'
    },
    tailwind: {
      foreground_color: 'bg-orange-500',
      background_color: 'bg-orange-300'
    }
  },
  5: {
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
