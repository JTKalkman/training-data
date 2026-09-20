import { ColorConfig } from "./color-config";
import { ColorZone } from "./color-zone";

export interface FieldColorConfig {
  strategy: 'static' | 'zones';
  default: ColorConfig;
  zones?: ColorZone[];
}
