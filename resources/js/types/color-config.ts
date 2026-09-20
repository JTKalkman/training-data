export interface ColorConfig {
  hex: { // For Chart.js.
   foreground_color: string;
   background_color: string;
  };
  tailwind: { // For DOM elements that can include darkmode.
    foreground_color: string;
    background_color: string;
  };
};
