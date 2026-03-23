<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useSampleData } from '@/composables/useSampleData';
import type { HeartRateZone, HoverPosition } from '@/types';
import type { SampleDataPoint } from '@/types/sample-data-point';
import TrainingSessionChart from './TrainingSessionChart.vue';
import Spinner from './ui/spinner/Spinner.vue';

const props = defineProps<{
  sessionId: string;
  heartRateZones: HeartRateZone[];
}>();

const { data, loading, error, fetch } = useSampleData(props.sessionId);

const fields = ['heart_rate', 'speed', 'cadence', 'altitude', 'pace'] as const;
const availableFields = computed(() => {
  if (!data.value?.length) return [];

  const first = data.value[0];
  return fields.filter(field => first[field] !== undefined);
});

const chartData = computed(() => {
  const xAxis = data.value?.map(row => row.time_label) || [];;

  const heart_rate = {
    label: 'Heart reate (bpm)',
    samples: [] as { x: number; y: number }[], // TODO: Upgrade to own type.
    metaData: {
      min: null,
      max: null,
      zones: [],
    }
  }

  const speed = {
    label: 'Speed (km/h)',
    samples: [] as { x: number; y: number }[], // TODO: Upgrade to own type.
    metaData: {
      min: null,
      max: null,
      zones: [],
    }
  }

  const pace = {
    label: 'Pace (min/km)',
    samples: [] as { x: number; y: number }[], // TODO: Upgrade to own type.
    metaData: {
      min: null,
      max: null,
      zones: [],
    }
  }

  const altitude = {
    label: 'Altitude (m)',
    samples: [] as { x: number; y: number }[], // TODO: Upgrade to own type.
    metaData: {
      min: null,
      max: null,
      zones: [],
    }
  }

  const cadence = {
    label: 'Cadence (steps/min)',
    samples: [] as { x: number; y: number }[], // TODO: Upgrade to own type.
    metaData: {
      min: null,
      max: null,
      zones: [],
    }
  }

  if (data.value?.length) {
    heart_rate.samples = data.value.map(row => ({ x: row.time, y: row.heart_rate }))
    speed.samples =  data.value
      .filter((row): row is SampleDataPoint & { speed: number } => row.speed !== undefined)
      .map(row => ({ x: row.time, y: row.speed }))
    pace.samples = data.value
        .filter((row): row is SampleDataPoint & { pace: number } => row.pace !== undefined)
        .map(row => ({ x: row.time, y: row.pace }))
    cadence.samples = data.value
      .filter((row): row is SampleDataPoint & { cadence: number } => row.cadence !== undefined)
      .map(row => ({ x: row.time, y: row.cadence }))
    altitude.samples = data.value
      .filter((row): row is SampleDataPoint & { altitude: number } => row.altitude !== undefined)
      .map(row => ({ x: row.time, y: row.altitude }))
  };

  if (heart_rate.samples) {
    const values = heart_rate.samples.map(s => s.y);
    heart_rate.metaData.min = values.reduce((a, b) => Math.min(a, b), Infinity);
    heart_rate.metaData.max = values.reduce((a, b) => Math.max(a, b), -Infinity);
  }

  if (speed.samples) {
    const values = speed.samples.map(s => s.y);
    speed.metaData.min = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    speed.metaData.max = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  if (pace.samples) {
    const values = speed.samples.map(s => s.y);
    pace.metaData.min = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    pace.metaData.max = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  if (altitude.samples) {
    const values = altitude.samples.map(s => s.y);
    altitude.metaData.min = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    altitude.metaData.max = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  if (cadence.samples) {
    const values = cadence.samples.map(s => s.y);
    cadence.metaData.min = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    cadence.metaData.max = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  return { xAxis, heart_rate, speed, pace, altitude, cadence };
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
  <div class="border rounded-xl p-4">
    <div v-if="loading" class="flex justify-center bg-gray-400 pt-10 pb-10">
      <Spinner />
    </div>
    
    <div v-if="error">
      <p class="text-center text-red-500 text-sm">
        {{ error }}
      </p>
    </div>

    <div class="mb-2 bg-white dark:bg-sidebar-accent border rounded-xl p-4">
      <div class="mb-2">
        <div v-for="field in availableFields" class="flex flex-col mb-4">

          <p class="mb-2">{{ chartData[field].label }}</p>

          <div class="flex">
            <div class="hidden lg:flex w-16 flex-col justify-between text-sm text-gray-500 dark:text-gray-300">
              <p class="text-nowrap">{{ chartData[field].metaData.max }}</p>
              <p class="text-nowrap">{{ chartData[field].metaData.min }}</p>
            </div>
      
            <div class="grow flex flex-col">
              <TrainingSessionChart
                :field="field"
                :data="chartData[field].samples"
                :chartHoverPosition="chartHoverPosition"
                :hoverSource="hoverSource"
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
