<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Chart from 'chart.js/auto'
import axios from 'axios'
import { route } from 'ziggy-js'
import { HeartRateDataPoint } from '@/types'

const chartCanvas = ref<HTMLCanvasElement | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)

let chartInstance = null

const props = defineProps<{
  sessionId: number;
}>()

const drawChart = (responseData: HeartRateDataPoint[]) => {
  const labels = responseData.map(d => d.time)
  const chartData = responseData.map(d => d.heart_rate)

  chartInstance = new Chart(chartCanvas.value!, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Heart Rate',
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
      scales: { x: { display: true }, y: { display: true } }
    }
  })
}

onMounted(() => {
  axios.get(route('sessions.raw-data', { session: props.sessionId }))
    .then(function (response) {
      drawChart(response.data)
    })
    .catch(function (error) {
      error.value = error
    })
    .finally(function () {
      loading.value = false
    });
})
</script>

<template>
  <div style="width:100%; height:300px;">
    <div v-if="loading">Loading chart…</div>
  
    <div v-if="error">{{ error }}</div>
    
    <div v-show="!loading" style="width:100%; height:300px;" class="bg-gray-100">
      <canvas ref="chartCanvas"></canvas>
    </div>
  </div>
</template>
