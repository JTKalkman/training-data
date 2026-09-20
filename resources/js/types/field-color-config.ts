import { ColorConfig } from "./color-config";
import { ColorZone } from "./color-zone";

export interface FieldColorConfig {
  strategy: 'static' | 'zones';
  fallback: ColorConfig; // Fallback colors.
  zones?: ColorZone[];
}
