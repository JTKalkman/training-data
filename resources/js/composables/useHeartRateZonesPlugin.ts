import { Zone } from '@/types';
import { Plugin } from 'chart.js';

export function createZonesPlugin(zones: Zone[]): Plugin<'line'> {
  return {
    id: 'zonesBackground',
    beforeDraw(chart) {
      const { ctx, chartArea: { left, top, width, height }, scales: { y } } = chart;
            
      ctx.save();
      ctx.globalCompositeOperation = 'destination-over';

      zones.forEach(zone => {
        const yTop    = y.getPixelForValue(zone.max);
        const yBottom = y.getPixelForValue(zone.min);

        ctx.fillStyle = zone.color;
        ctx.fillRect(left, yTop, width, yBottom - yTop);
      });

      ctx.restore();
    }
  };
}
