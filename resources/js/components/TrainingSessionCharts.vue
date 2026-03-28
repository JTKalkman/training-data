<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
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

// Hovers and tooltips.
const chartHoverPosition = ref<HoverPosition | null>(null);
const hoverSource = ref<string | null>(null);
const hoverData = ref<Record<string, number | null>>({});

const tooltipData = computed(() => {
    if (!chartHoverPosition.value) return null;
    
    const index = chartHoverPosition.value.index;
    
    return {
        heart_rate: chartData.value.datasets['heart_rate']?.samples[index]?.y,
        pace:       chartData.value.datasets['pace']?.samples[index]?.y,
        speed:      chartData.value.datasets['speed']?.samples[index]?.y,
        cadence:    chartData.value.datasets['cadence']?.samples[index]?.y,
        altitude:   chartData.value.datasets['altitude']?.samples[index]?.y,
    };
});

const chartsContainer = ref<HTMLElement | null>(null);
const containerHeight = ref(0);
const containerWidth = ref(0);

const yAxisWidth = computed(() => {
  // w-16 = 64px, only on lg+
  return window.innerWidth >= 1024 ? 64 : 0;
});

const tooltipOnRight = computed(() => 
    (chartHoverPosition.value?.x ?? 0) < containerWidth.value / 2
);

const handleChartHover = (position: HoverPosition | null, sourceField: string | null) => {
  if (!position) {
    hoverData.value = {};
    chartHoverPosition.value = null;
    return;
  }

  chartHoverPosition.value = position;
  hoverSource.value = sourceField;
}

onMounted(() => {
  if (chartsContainer.value) {
    const observer = new ResizeObserver((entries) => {
      const entry = entries[0];
      containerHeight.value = entry.contentRect.height;
      containerWidth.value = entry.contentRect.width;
    });
        
    observer.observe(chartsContainer.value);
        
    onUnmounted(() => observer.disconnect());
  }

  fetch();
})
</script>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.15s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>

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

    <div class="mb-2 relative" >
      <!-- Tooltip -->
      <div class="relative">
        <Transition name="fade">
          <div
            v-if="chartHoverPosition && tooltipData"
            class="absolute w-48 z-10 pointer-events-none bg-white border border-gray-200 rounded-lg shadow-lg px-3 py-2 text-sm text-gray-800"
            :style="{
              top: `${containerHeight / 2}px`,
              transform: `translateX(${tooltipOnRight ? '0' : '-100%'}) translateY(-50%)`,
              left: tooltipOnRight
                  ? `calc(${chartHoverPosition.x}px + ${yAxisWidth}px + 1em)`
                  : `calc(${chartHoverPosition.x}px + ${yAxisWidth}px - 1em)`,
            }"
          >
            <div class="grid grid-cols-2 gap-x-2 gap-y-1 text-nowrap">
              <span v-if="tooltipData.heart_rate" class="">Heart rate</span>
              <span v-if="tooltipData.heart_rate" class="font-medium text-right">{{ tooltipData.heart_rate }} bpm</span>

              <span v-if="tooltipData.speed && availableFields.includes('speed')" class="">Speed</span>
              <span v-if="tooltipData.speed && availableFields.includes('speed')" class="font-medium text-right">{{ tooltipData.speed }} km/h</span>

              <span v-if="tooltipData.pace && availableFields.includes('pace')" class="">Pace</span>
              <span v-if="tooltipData.pace && availableFields.includes('pace')" class="font-medium text-right">{{ formatPace(tooltipData.pace) }} min/km</span>

              <span v-if="tooltipData.cadence" class="">Cadence</span>
              <span v-if="tooltipData.cadence" class="font-medium text-right">{{ tooltipData.cadence }} spm</span>

              <span v-if="tooltipData.altitude" class="">Altitude</span>
              <span v-if="tooltipData.altitude" class="font-medium text-right">{{ tooltipData.altitude }} m</span>
            </div>
          </div>
        </Transition>
      </div>

      <!-- Charts container -->
      <div class="mb-2 relative" ref="chartsContainer">
        <div 
          v-for="field in availableFields"
          :key="field" 
          class="flex flex-col mb-4"
        >

          <p class="mb-2 text-sm font-medium">{{ chartData.datasets[field].label }}</p>

          <div class="flex">
            <div class="hidden lg:flex w-16 lg:shrink-0 flex-col justify-between text-sm text-gray-500 dark:text-gray-300">
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

      <!-- X-axis -->
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
