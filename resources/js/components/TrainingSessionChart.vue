<script setup lang="ts">

import { 
  Chart, CategoryScale, LinearScale, LineController, PointElement, 
  LineElement, Tooltip, 
  ChartConfiguration
} from 'chart.js';
import { onMounted, ref, watch } from 'vue';
import { useIsMobile } from '@/composables/useIsMobile';
import type { ChartDataPoint } from '@/types/chart-data-point';

const props = defineProps<{
  field: string;
  data: Array<ChartDataPoint>;
  reverse: boolean;
  min: number|null;
  max: number|null;
}>();

const emit = defineEmits(['hover']);

const chartCanvas = ref<HTMLCanvasElement | null>(null);

let chartInstance: Chart;
let lastEmittedIndex: number | null = null;

const drawChart = () => {
  const isMobile = useIsMobile();
  const labels = props.data.map(d => d.x)
  const chartData = props.data.map(d => d.y)

  Chart.register(CategoryScale, LinearScale, LineController, PointElement, LineElement, Tooltip)

  const config: ChartConfiguration<'line'> = {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: props.field,
        data: chartData,
        borderColor: 'gray',
        borderWidth: 1,
        pointRadius: 0,
        pointHoverRadius: 0,
        fill: false,
        tension: 0,
      }],
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
