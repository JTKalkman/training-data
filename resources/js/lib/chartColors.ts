import { ColorConfig, FieldColorConfig } from "@/types";

export const chartColors: Record<string, FieldColorConfig> = {
  heart_rate: {
    strategy: 'zones',
    default: {
      hex: {
        foreground_color: '#3b82f6',
        background_color: '#3b82f6'
      },
      tailwind: {
        foreground_color: 'bg-blue-500',
        background_color: 'bg-blue-500'
      } 
    }
    // zones populated at call time from heartRateZones prop, see below.
  },
  speed:    { 
    strategy: 'static', 
    default: {
      hex: {
        foreground_color: '#74d4ff',
        background_color: '#dff2fe'
      },
      tailwind: {
        foreground_color: 'bg-sky-300',
        background_color: 'bg-sky-100'
      }
    }
  },
  pace:     { 
    strategy: 'static', 
    default: {
      hex: {
        foreground_color: '#74d4ff',
        background_color: '#dff2fe'
      },
      tailwind: {
        foreground_color: 'bg-sky-300',
        background_color: 'bg-sky-100'
      }
    }
  },
  cadence:  { 
    strategy: 'static', 
    default: {
      hex: {
        foreground_color: '#74d4ff',
        background_color: '#dff2fe'
      },
      tailwind: {
        foreground_color: 'bg-sky-300',
        background_color: 'bg-sky-100'
      }
    }
  },
  altitude: { 
    strategy: 'static',
    default: {
      hex: {
        foreground_color: '#90a1b9',
        background_color: '#e2e8f0'
      },
      tailwind: {
        foreground_color: 'bg-slate-400',
        background_color: 'bg-slate-200'
      }
    }
  },
};

// Defaults/fallback in case if missing above.
export const chartDefaultColors: ColorConfig = {
  hex: {
    foreground_color: '#90a1b9',
    background_color: '#e2e8f0'
  },
  tailwind: {
    foreground_color: 'bg-slate-400',
    background_color: 'bg-slate-200'
  }
};
