<script setup lang="ts">
import { ChartDataPoint } from '@/types/ChartDataPoint';
import Chart from 'chart.js/auto';
import { onMounted, ref } from 'vue';

const props = defineProps<{
  field: string;
  data: Array<ChartDataPoint>
}>();

const chartCanvas = ref<HTMLCanvasElement | null>(null);
let chartInstance = null;

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
      scales: { x: { display: false }, y: { display: false } }
    }
  })
}

onMounted(() => {
  drawChart();
})
</script>

<template>
  <div style="width:100%; height:300px;">
    <div style="width:100%; height:300px;" class="bg-gray-100">
      <canvas ref="chartCanvas"></canvas>
    </div>
  </div>
</template>
