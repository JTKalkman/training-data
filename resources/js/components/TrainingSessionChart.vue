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
          display: true,
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
          display: true,
          reverse: props.reverse,
          ticks: {
            display: false,
            padding: 0,
          },
          border: {
            display: false,
          },
          position: 'right'
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
        tooltip: {
          enabled: false,
        }
      }
    },
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
