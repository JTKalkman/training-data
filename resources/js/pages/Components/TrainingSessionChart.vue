<script setup lang="ts">
import { HoverPosition } from '@/types';
import { ChartDataPoint } from '@/types/chart-data-point';
import Chart, { ChartTypeRegistry } from 'chart.js/auto';
import { onMounted, ref, watch } from 'vue';

const props = defineProps<{
  field: string;
  data: Array<ChartDataPoint>;
  chartHoverPosition: HoverPosition | null;
  hoverSource: string | null;
}>();

const emit = defineEmits(['hover']);

const chartCanvas = ref<HTMLCanvasElement | null>(null);

let chartInstance: null | Chart<keyof ChartTypeRegistry, number[], number> = null;

watch(() => props.chartHoverPosition, (position) => {
  if (props.hoverSource === props.field) return;

  if (position) {
    showTooltip(position);
  } else {
    destroyTooltip();
  }
});

const showTooltip = (position: HoverPosition) => {
  chartInstance?.tooltip?.setActiveElements(
    [{
      datasetIndex: 0,
      index: position.index
    }], 
    { x: position.x, y: 0 }
  );
  chartInstance?.update();
}

const destroyTooltip = () => {
  chartInstance?.tooltip?.setActiveElements([], { x: 0, y: 0 });
  chartInstance?.update();
}

const crosshairPlugin = {
  id: 'crosshair',
  afterDraw(chart: Chart) {
    const tooltip = chart.tooltip as any; // TODO: A bit ugly for only one property.
    if (tooltip?._active?.length) {
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
  const labels = props.data.map(d => d.x)
  const chartData = props.data.map(d => d.y)

  chartInstance = new Chart(chartCanvas.value!, {
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
      scales: { x: { display: false }, y: { display: false } },
      interaction: {
        intersect: false,
        mode: 'index',
      },
      onHover: (event, activeElements) => {
        if (activeElements.length > 0) {
          emit('hover', { index: activeElements[0].index, x: event.x, }, props.field);
        } else {
          emit('hover', null, props.field)
        }
      }
    },
    plugins: [crosshairPlugin]
  })
}

onMounted(() => {
  drawChart();
})
</script>

<template>
  <div style="width:100%; height:200px;">
    <div style="width:100%; height:200px;" class="bg-gray-100">
      <canvas ref="chartCanvas" @mouseleave="emit('hover', null, props.field)"></canvas>
    </div>
  </div>
</template>
