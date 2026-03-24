<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { usePace } from '@/composables/usePace';
import { useSampleData } from '@/composables/useSampleData';
import type { ChartData, ChartDataSet, HeartRateZone, HoverPosition } from '@/types';
import type { SampleDataPoint } from '@/types/sample-data-point';
import TrainingSessionChart from './TrainingSessionChart.vue';
import Spinner from './ui/spinner/Spinner.vue';

const props = defineProps<{
  sessionId: string;
  heartRateZones: HeartRateZone[];
  fields?: string[];
}>();

const { data, loading, error, fetch } = useSampleData(props.sessionId);
const { formatPace } = usePace();

const allFields = ['heart_rate', 'speed', 'pace', 'cadence', 'altitude'] as const;
const fields = <string[]>props.fields ?? allFields;

const availableFields = computed(() => {
  if (!data.value?.length) return [];

  const firstDataPoint: SampleDataPoint = data.value[0];
  return fields.filter(field => firstDataPoint[field] !== undefined);
});

const chartData = computed<ChartData>(() => {
  const xAxis = data.value?.map(row => row.time_label) || [];
  const datasets = {
    heart_rate: <ChartDataSet>{
      label: 'Heart reate (bpm)',
      samples: [],
      metaData: {
        min: null,
        max: null,
      },
      reverse: false,
    },
    speed: <ChartDataSet>{
      label: 'Speed (km/h)',
      samples: [],
      metaData: {
        min: null,
        max: null,
      },
      reverse: false,
    },
    pace: <ChartDataSet>{
      label: 'Pace (min/km)',
      samples: [],
      metaData: {
        min: null,
        max: null,
      },
      reverse: true,
    },
    altitude: <ChartDataSet>{
      label: 'Altitude (m)',
      samples: [],
      metaData: {
        min: null,
        max: null,
      },
      reverse: false,
    },
    cadence: <ChartDataSet>{
      label: 'Cadence (steps/min)',
      samples: [],
      metaData: {
        min: null,
        max: null,
      },
      reverse: false,
    },
  };

  if (data.value?.length) {
    datasets.heart_rate.samples = data.value.map(row => ({ x: row.time, y: row.heart_rate }))
    datasets.speed.samples =  data.value
      .filter((row): row is SampleDataPoint & { speed: number } => row.speed !== undefined)
      .map(row => ({ x: row.time, y: row.speed }))
    datasets.pace.samples = data.value
        .filter((row): row is SampleDataPoint & { pace: number } => row.pace !== undefined)
        .map(row => ({ x: row.time, y: row.pace }))
    datasets.cadence.samples = data.value
      .filter((row): row is SampleDataPoint & { cadence: number } => row.cadence !== undefined)
      .map(row => ({ x: row.time, y: row.cadence }))
    datasets.altitude.samples = data.value
      .filter((row): row is SampleDataPoint & { altitude: number } => row.altitude !== undefined)
      .map(row => ({ x: row.time, y: row.altitude }))
  };

  if (datasets.heart_rate.samples) {
    const values = datasets.heart_rate.samples.map(s => s.y);
    datasets.heart_rate.metaData.min = values.reduce((a, b) => Math.min(a, b), Infinity);
    datasets.heart_rate.metaData.max = values.reduce((a, b) => Math.max(a, b), -Infinity);
  }

  if (datasets.speed.samples) {
    const values = datasets.speed.samples.map(s => s.y);
    datasets.speed.metaData.min = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    datasets.speed.metaData.max = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  if (datasets.pace.samples) {
    const values = datasets.pace.samples.map(s => s.y);
    datasets.pace.metaData.max = formatPace(Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity)));
    datasets.pace.metaData.min = formatPace(Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity)));
  }

  if (datasets.altitude.samples) {
    const values = datasets.altitude.samples.map(s => s.y);
    datasets.altitude.metaData.min = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    datasets.altitude.metaData.max = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  if (datasets.cadence.samples) {
    const values = datasets.cadence.samples.map(s => s.y);
    datasets.cadence.metaData.min = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    datasets.cadence.metaData.max = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  return { xAxis, datasets };
})

const chartHoverPosition = ref<HoverPosition | null>(null);
const hoverSource = ref<string | null>(null);

const handleChartHover = (position: HoverPosition | null, sourceField: string | null) => {
  chartHoverPosition.value = position;
  hoverSource.value = sourceField;
}

onMounted(() => {
  fetch();
})
</script>

<template>
  <div class="border rounded-xl p-4 bg-white dark:bg-sidebar-accent">
    <div v-if="loading" class="flex justify-center bg-gray-400 pt-10 pb-10">
      <Spinner />
    </div>
    
    <div v-if="error">
      <p class="text-center text-red-500 text-sm">
        {{ error }}
      </p>
    </div>

    <div class="mb-2">
      <div class="mb-2">
        <div 
          v-for="field in availableFields"
          :key="field" 
          class="flex flex-col mb-4"
        >

          <p class="mb-2 text-sm font-medium">{{ chartData.datasets[field].label }}</p>

          <div class="flex">
            <div class="hidden lg:flex w-16 flex-col justify-between text-sm text-gray-500 dark:text-gray-300">
              <p class="text-nowrap">{{ chartData.datasets[field].metaData.max }}</p>
              <p class="text-nowrap">{{ chartData.datasets[field].metaData.min }}</p>
            </div>
      
            <div class="grow flex flex-col border-b-2 border-l-2">
              <TrainingSessionChart
                :field="field"
                :data="chartData.datasets[field].samples"
                :chartHoverPosition="chartHoverPosition"
                :hoverSource="hoverSource"
                :reverse="chartData.datasets[field].reverse"
                @hover="handleChartHover"
              />
            </div>
      
            <!-- <div class="hidden lg:flex w-16">
              <p>zones</p>
            </div> -->
          </div>
        </div>
      </div>

      <div class="lg:pl-16">
        <div class="flex justify-between text-sm text-gray-500 dark:text-gray-300">
          <p>{{ chartData.xAxis[0] }}</p>
          <p class="hidden lg:block">{{ chartData.xAxis[Math.floor(chartData.xAxis.length * .25)] }}</p>
          <p class="hidden lg:block">{{ chartData.xAxis[Math.floor(chartData.xAxis.length *.5 )] }}</p>
          <p class="hidden lg:block">{{ chartData.xAxis[Math.floor(chartData.xAxis.length * .75)] }}</p>
          <p>{{ chartData.xAxis[chartData.xAxis.length - 1] }}</p>
        </div>
      </div>
    </div>
  
  </div>
  
</template> 
