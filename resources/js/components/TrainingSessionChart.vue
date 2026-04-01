<script setup lang="ts">
import type { TooltipPositionerFunction, ChartType, Plugin 
} from 'chart.js';
import { 
  Chart, CategoryScale, LinearScale, LineController, PointElement, 
  LineElement, Tooltip 
} from 'chart.js';
import { computed, onMounted, ref, watch } from 'vue';
import { useIsMobile } from '@/composables/useIsMobile';
import type { HoverPosition, Zone } from '@/types';
import type { ChartDataPoint } from '@/types/chart-data-point';
import { createZonesPlugin } from '@/composables/useHeartRateZonesPlugin';

const props = withDefaults(defineProps<{
  field: string;
  data: Array<ChartDataPoint>;
  chartHoverPosition: HoverPosition | null;
  hoverSource: string | null;
  reverse: boolean;
  yMin?: number | null;
  yMax?: number | null;
  zones?: Zone[];
  lineColor?: string;
}>(), {
    reverse: false,
    zones: () => [],
    lineColor: 'rgb(99, 102, 241)',   // indigo als default
    // fillColor: 'rgba(99, 102, 241, 0.1)',
});

const emit = defineEmits(['hover'])
const chartCanvas = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart;

const plugins = computed(() => {
  const pluginList: Plugin<'line'>[] = [crosshairPlugin];

  if (props.zones?.length) {
    pluginList.unshift(createZonesPlugin(props.zones));
  }

  return pluginList;
});

watch(() => props.chartHoverPosition, (position) => {
  if (props.hoverSource === props.field) return;

  if (position) {
    showTooltip(position);
  } else {
    destroyTooltip();
  }
});

const showTooltip = (position: HoverPosition) => {
  chartInstance.tooltip?.setActiveElements(
    [{
      datasetIndex: 0,
      index: position.index
    }], 
    { x: position.x, y: 0 }
  );
  chartInstance.update('none');
}

const destroyTooltip = () => {
  chartInstance.tooltip?.setActiveElements([], { x: 0, y: 0 });
  chartInstance.update();
}

const crosshairPlugin = {
  id: 'crosshair',
  afterDraw(chart: Chart) {
    const tooltip = chart.tooltip as any; // TODO: A bit ugly for only one property.
    if (tooltip && tooltip._active?.length) {
      const x = tooltip._active[0].element.x;
      const ctx = chart.ctx;
      const topY = chart.scales.y.top;
      const bottomY = chart.scales.y.bottom;

      ctx.save();
      ctx.beginPath();
      ctx.moveTo(x, topY);
      ctx.lineTo(x, bottomY);
      ctx.lineWidth = 1;
      ctx.strokeStyle = '#6B7280';
      ctx.stroke();
      ctx.restore();
    }
  }
};

const drawChart = () => {
  const isMobile = useIsMobile();
  const labels = props.data.map(d => d.x)
  const chartData = props.data.map(d => d.y)

  Chart.register(CategoryScale, LinearScale, LineController, PointElement, LineElement, Tooltip)

  const xScales = {
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
  };

  const yScales = {
    display: false,
    reverse: props.reverse,
    ticks: {
      display: false,
      padding: 0,
    },
    border: {
      display: false,
    },
    position: 'right',
  };

  if (props.yMin !== null) {
    yScales.min = props.yMin;
  }

  if (props.yMax !== null) {
    yScales.max = props.yMax;
  }

  chartInstance = new Chart(chartCanvas.value!, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: props.field,
        data: chartData,
        borderColor: props.lineColor,
        borderWidth: 1.5,
        pointRadius: 0,
        pointHoverRadius: 0,
        fill: false,
        tension: 2,
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
        x: xScales,
        y: yScales,
      },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      events: isMobile ? ['click'] : ['mousemove', 'mouseout', 'click', 'touchstart', 'touchmove'],
      onHover: (event, activeElements) => {
        if (activeElements.length > 0) {
          emit('hover', { index: activeElements[0].index, x: event.x, }, props.field);
        } else {
          emit('hover', null, props.field)
        }
      },
      plugins: {
        tooltip: {
          enabled: false,
        }
      }
    },
    plugins: plugins.value
  })
}

onMounted(() => {
  drawChart();
})
</script>

<template>
  <div class="w-full h-28">
    <canvas 
      ref="chartCanvas" 
      @mouseleave="emit('hover', null, props.field)"
    ></canvas>
  </div>
</template>
