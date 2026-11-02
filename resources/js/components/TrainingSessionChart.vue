<script setup lang="ts">

import { 
  Chart, CategoryScale, LinearScale, LineController, PointElement, 
  LineElement, ChartConfiguration
} from 'chart.js';
import { onMounted, ref } from 'vue';
import { useIsMobile } from '@/composables/useIsMobile';
import type { ChartDataPoint } from '@/types/chart-data-point';
import { getZoneColor } from '@/lib/getZoneColor';
import { ColorZone } from '@/types';
import { chartColors, chartDefaultColors } from '@/lib/chartColors';

const props = defineProps<{
  field: string;
  data: ChartDataPoint[];
  zones: ColorZone[];
  reverse: boolean;
  min: number|null;
  max: number|null;
}>();

const emit = defineEmits(['hover']);

const chartCanvas = ref<HTMLCanvasElement | null>(null);

let chartInstance: Chart;
let lastEmittedIndex: number | null = null;

const zoneBackgroundPlugin = {
  id: 'zoneBackground',
  beforeDatasetsDraw(chart: Chart, _args: unknown, options: { zones?: ColorZone[] }) {
    const zones = options?.zones;
    if (!zones?.length) return; // speed, altitude, or no zones: no bands

    const { ctx, chartArea, scales } = chart;
    const { min: axisMin, max: axisMax } = scales.y;
    const sorted = [...zones].sort((a, b) => a.min - b.min);

    ctx.save();
    ctx.beginPath();
    ctx.rect(chartArea.left, chartArea.top, chartArea.width, chartArea.height);
    ctx.clip();
    ctx.globalAlpha = 0.5; // soften the background colors; tune to taste

    sorted.forEach((zone, i) => {
      const isFirst = i === 0;
      const isLast = i === sorted.length - 1;

      const from = isFirst ? Math.min(zone.min, axisMin) : zone.min;
      const to = isLast ? Math.max(zone.max, axisMax) : sorted[i + 1].min;

      const a = scales.y.getPixelForValue(from);
      const b = scales.y.getPixelForValue(to);

      ctx.fillStyle = zone.colorConfig.hex.background_color;
      ctx.fillRect(chartArea.left, Math.min(a, b), chartArea.width, Math.abs(a - b));
    });

    ctx.restore();
  },
};

const drawChart = () => {
  Chart.register(CategoryScale, LinearScale, LineController, PointElement, LineElement)

  const isMobile = useIsMobile();
  const labels = props.data.map(d => d.x)
  const chartData = props.data.map(d => d.y)
  const fieldColorConfig = chartColors[props.field]
  const borderColor = fieldColorConfig?.default?.hex?.foreground_color || chartDefaultColors.hex.foreground_color;

  const datasetConfig: ChartConfiguration<'line'>['data']['datasets'][0] = {
    label: props.field,
    data: chartData,
    borderColor,
    borderWidth: 1,
    pointRadius: 0,
    pointHoverRadius: 0,
    fill: false,
    tension: 0,
  };

  if (fieldColorConfig && fieldColorConfig.strategy === 'zones' && props.zones) {
    datasetConfig.segment = {
      borderColor: (ctx) => {
        const value = ctx.p1.parsed.y ?? null;
        const colorConfig = getZoneColor(value, props.zones!, chartDefaultColors)
        return colorConfig.hex.foreground_color;
      },
    };
  }

  const config: ChartConfiguration<'line'> = {
    type: 'line',
    data: {
      labels: labels,
      datasets: [ datasetConfig ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: false,
      normalized: true,
      layout: {
        padding: {
          left: 0,
          bottom: 0,
        }
      },
      scales: {
        x: {
          display: false,
          ticks: {
            display: false,
          },
          grid: {
            display: false
          },
          border: {
            display: false,
          },
          position: 'top',
        },
        y: {
          display: false,
          reverse: props.reverse,
          ticks: {
            display: false,
            padding: 0,
          },
          border: {
            display: false,
            width: 0,
          },
          position: 'right',
        }
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      events: isMobile ? ['click'] : ['mousemove', 'mouseout', 'click', 'touchstart', 'touchmove'],
      onHover: (event, activeElements) => {
        if (activeElements.length > 0) {
          const index = activeElements[0].index;

          if (index === lastEmittedIndex) return; // Same data point, nothing changed
          lastEmittedIndex = index;
          emit(
            'hover',
            { index, x: event.x, time: props.data[index]?.x, }
          );
        } else {
          if (lastEmittedIndex === null) return; // Already cleared, nothing to do

          emit('hover', null)
        }
      },
      plugins: {
        tooltip: { enabled: false, },
        zoneBackground: { zones: props.zones },
      },
    },
    plugins: [zoneBackgroundPlugin],
  }

  const yScale = config?.options?.scales?.y
  if (yScale) {
    if (props.min !== null) yScale.min = props.min
    if (props.max !== null) yScale.max = props.max
  }

  chartInstance = new Chart(chartCanvas.value!, config)
}

onMounted(() => {
  drawChart();
})
</script>

<template>
  <div class="w-full h-28">
    <canvas 
      ref="chartCanvas" 
      @mouseleave="emit('hover', null)"
      class=""
    ></canvas>
  </div>
</template>
