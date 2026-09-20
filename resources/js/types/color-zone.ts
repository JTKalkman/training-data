import { ColorConfig } from "./color-config";

export interface ColorZone {
  min: number;
  max: number;
  colorConfig: ColorConfig | null;
}
