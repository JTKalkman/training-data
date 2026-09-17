<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { usePace } from '@/composables/usePace';
import { useSampleData } from '@/composables/useSampleData';
import type { ChartData, ChartDataSet, HeartRateZone, HoverPosition, RunningPaceZone } from '@/types';
import type { SampleDataPoint } from '@/types/sample-data-point';
import TrainingSessionChart from './TrainingSessionChart.vue';
import Spinner from './ui/spinner/Spinner.vue';
import { getZoneForHeartRate, zoneColorClasses } from '@/lib/heartRateZones.js';

const props = defineProps<{
  sessionId: string;
  heartRateZones: HeartRateZone[];
  runningPaceZones: RunningPaceZone[];
  fields?: string[];
}>();

const emit = defineEmits<{
  hover: [position: HoverPosition | null];
}>();

const { data, loading, error, fetch } = useSampleData(props.sessionId);
const { formatPace } = usePace();

const allFields = ['distance', 'heart_rate', 'speed', 'pace', 'cadence', 'altitude'] as const;
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
        minStr: null,
        maxStr: null
      },
      reverse: false,
    },
    speed: <ChartDataSet>{
      label: 'Speed (km/h)',
      samples: [],
      metaData: {
        min: null,
        max: null,
        minStr: null,
        maxStr: null
      },
      reverse: false,
    },
    pace: <ChartDataSet>{
      label: 'Pace (min/km)',
      samples: [],
      metaData: {
        min: null,
        max: null,
        minStr: null,
        maxStr: null
      },
      reverse: true,
    },
    altitude: <ChartDataSet>{
      label: 'Altitude (m)',
      samples: [],
      metaData: {
        min: null,
        max: null,
        minStr: null,
        maxStr: null
      },
      reverse: false,
    },
    cadence: <ChartDataSet>{
      label: 'Cadence (steps/min)',
      samples: [],
      metaData: {
        min: null,
        max: null,
        minStr: null,
        maxStr: null
      },
      reverse: false,
    },
    distance: <ChartDataSet>{
      label: 'Distance (km)',
      samples: [],
      metaData: {
        min: null,
        max: null,
        minStr: null,
        maxStr: null
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
    datasets.distance.samples = data.value
      .filter((row): row is SampleDataPoint & { distance: number } => row.distance !== undefined)
      .map(row => ({ x: row.time, y: row.distance }))
  };

  if (datasets.heart_rate.samples) {
    const values = datasets.heart_rate.samples.map(s => s.y);

    const heartRateSamplesMin = values.reduce((a, b) => Math.min(a, b), Infinity);
    const heartRateSampleMax = values.reduce((a, b) => Math.max(a, b), -Infinity);

    const heartRateZoneMin = props.heartRateZones.reduce((a, b) => Math.min(a, b.min_bpm), Infinity);
    const heartRateZoneMax = props.heartRateZones.reduce((a, b) => Math.max(a, b.max_bpm), -Infinity);

    datasets.heart_rate.metaData.min = datasets.heart_rate.metaData.minStr = Math.min(heartRateSamplesMin, heartRateZoneMin);
    datasets.heart_rate.metaData.max = datasets.heart_rate.metaData.maxStr = Math.max(heartRateSampleMax, heartRateZoneMax);
  }

  if (datasets.speed.samples) {
    const values = datasets.speed.samples.map(s => s.y);
    datasets.speed.metaData.min = datasets.speed.metaData.minStr = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    datasets.speed.metaData.max = datasets.speed.metaData.maxStr = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  if (datasets.pace.samples) {
    const values = datasets.pace.samples.map(s => s.y);

    const paceSamplesMin = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    const paceSamplesMax = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));

    const paceZoneMin = props.runningPaceZones.reduce((a, b) => Math.min(a, b.minSeconds), Infinity);
    const paceZoneMax = props.runningPaceZones.reduce((a, b) => Math.max(a, b.maxSeconds), -Infinity);

    datasets.pace.metaData.max = Math.min(paceSamplesMin, paceZoneMin); // Reversed, max is slowest.
    datasets.pace.metaData.min = Math.max(paceSamplesMax, paceZoneMax); // Reversed, min is fastest.

    datasets.pace.metaData.minStr = formatPace(datasets.pace.metaData.min);
    datasets.pace.metaData.maxStr = formatPace(datasets.pace.metaData.max);
  }

  if (datasets.altitude.samples) {
    const values = datasets.altitude.samples.map(s => s.y);
    datasets.altitude.metaData.min = datasets.altitude.metaData.minStr = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    datasets.altitude.metaData.max = datasets.altitude.metaData.maxStr = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  if (datasets.cadence.samples) {
    const values = datasets.cadence.samples.map(s => s.y);
    datasets.cadence.metaData.min = datasets.cadence.metaData.minStr = Math.floor(values.reduce((a, b) => Math.min(a, b), Infinity));
    datasets.cadence.metaData.max = datasets.cadence.metaData.maxStr = Math.ceil(values.reduce((a, b) => Math.max(a, b), -Infinity));
  }

  return { xAxis, datasets };
})

const HoverPositionStyle = computed(() => {
  if (!chartHoverPosition.value) return {};

  return {
    transform: `translateX(${tooltipOnRight.value ? '0' : '-100%'})`,
    left: tooltipOnRight.value
      ? `calc(${chartHoverPosition.value.x}px + ${yAxisWidth.value}px - 1em)`
      : `calc(${chartHoverPosition.value.x}px + ${yAxisWidth.value}px + 1em)`,
  };
});

// Hovers and tooltips.
const chartHoverPosition = ref<HoverPosition | null>(null);
const hoverData = ref<Record<string, number | null>>({});

const tooltipData = computed(() => {
    if (!chartHoverPosition.value) return null;
    
    const index = chartHoverPosition.value.index;
    
    return {
      distance:   chartData.value.datasets['distance']?.samples[index]?.y,
      heart_rate: chartData.value.datasets['heart_rate']?.samples[index]?.y,
      pace:       chartData.value.datasets['pace']?.samples[index]?.y,
      speed:      chartData.value.datasets['speed']?.samples[index]?.y,
      cadence:    chartData.value.datasets['cadence']?.samples[index]?.y,
      altitude:   chartData.value.datasets['altitude']?.samples[index]?.y,
    };
});

const hoverTimeLabel = computed(() => {
  if (!chartHoverPosition.value) return null;
  return chartData.value.xAxis[chartHoverPosition.value.index] ?? null;
});

const hoverHeartRateZone = computed(() => {
  if (!chartHoverPosition.value) return null;

  const heartRate = chartData.value.datasets.heart_rate.samples[chartHoverPosition.value.index]?.y
  if (!heartRate) return null;

  return getZoneForHeartRate(heartRate, props.heartRateZones);
})

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

const handleChartHover = (position: HoverPosition | null) => {
  if (!position) {
    hoverData.value = {};
    chartHoverPosition.value = null;
    emit('hover', null);
    return;
  }

  chartHoverPosition.value = position;
  emit('hover', position);
}

const formatValueFor = (field: string): string | null => {
  const v = tooltipData.value;

  if (!v) return null;

  switch (field) {
    case 'altitude':   return v.altitude != null ? `${v.altitude} m` : null;
    case 'cadence':    return v.cadence != null ? `${v.cadence} spm` : null;
    case 'distance':   return v.distance != null ? `${(v.distance / 1000).toFixed(2)} km` : null;
    case 'heart_rate': return v.heart_rate != null ? `${v.heart_rate} bpm` : null;
    case 'pace':       return v.pace != null ? `${formatPace(v.pace)} min/km` : null;
    case 'speed':      return v.speed != null ? `${v.speed} km/h` : null;
    default: return null;
  }
};

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
      <!-- Shared crosshair line -->
      <div
        v-if="chartHoverPosition"
        class="absolute top-0 bottom-0 w-px bg-gray-400 pointer-events-none z-1"
        :style="{ left: `${chartHoverPosition.x + yAxisWidth}px` }"
      ></div>

      <!-- Charts container -->
      <div class="mb-2 relative" ref="chartsContainer">
        <div 
          v-for="field in availableFields"
          :key="field" 
          class="flex flex-col mb-4"
        >

          <!-- Label and hover container for each chart. -->
          <div class="relative mb-2">
            <span class="font-medium text-sm">{{ chartData.datasets[field].label }}</span>
            <div
              v-if="chartHoverPosition"
              class="
                absolute top-0 bg-mist-50 shadow-md rounded-xs overflow-hidden
                dark:text-mist-800 tabular-nums text-nowrap flex z-1
              "
              :style="HoverPositionStyle"
              >
              <span
                v-if="field === 'heart_rate' && hoverHeartRateZone && hoverHeartRateZone.color"
                class="text-lg w-2"
                :class="zoneColorClasses(hoverHeartRateZone.color)"
              ></span>
              <span class="text-xs font-medium px-2 py-1 flex gap-x-1">
                <span>{{ formatValueFor(field) }}</span>
                <span
                  v-if="field === 'heart_rate' && hoverHeartRateZone && hoverHeartRateZone.name"
                >({{ hoverHeartRateZone.name }})</span>
              </span>
            </div>
          </div>

          <div class="flex">
            <div class="hidden lg:flex w-16 lg:shrink-0 flex-col justify-between text-sm text-gray-500 dark:text-gray-300">
              <p class="text-nowrap">{{ chartData.datasets[field].metaData.maxStr }}</p>
              <p class="text-nowrap">{{ chartData.datasets[field].metaData.minStr }}</p>
            </div>
      
            <div class="grow flex flex-col border-b-2 border-l-2">
              <TrainingSessionChart
                :field="field"
                :data="chartData.datasets[field].samples"
                :reverse="chartData.datasets[field].reverse"
                :min="chartData.datasets[field].metaData.min"
                :max="chartData.datasets[field].metaData.max"
                @hover="handleChartHover"
              />
            </div>

          </div>
        </div>
      </div>

      <!-- X-axis -->
      <div class="lg:pl-16 mb-3">
        <div class="flex justify-between text-sm text-gray-500 dark:text-gray-300">
          <p>{{ chartData.xAxis[0] }}</p>
          <p class="hidden lg:block">{{ chartData.xAxis[Math.floor(chartData.xAxis.length * .25)] }}</p>
          <p class="hidden lg:block">{{ chartData.xAxis[Math.floor(chartData.xAxis.length *.5 )] }}</p>
          <p class="hidden lg:block">{{ chartData.xAxis[Math.floor(chartData.xAxis.length * .75)] }}</p>
          <p>{{ chartData.xAxis[chartData.xAxis.length - 1] }}</p>
        </div>
      </div>

      <!-- Time/distance tooltip -->
      <div class="relative pl-16 pr-1 mb-5 text-sm text-gray-500 dark:text-gray-300 h-3">
        <div
          v-if="chartHoverPosition && (tooltipData?.distance || hoverTimeLabel)"
          class="
            absolute top-0 bg-mist-50 shadow-md rounded-xs overflow-hidden
            dark:text-mist-800 tabular-nums text-nowrap z-1
            text-xs font-medium px-2 py-1
          "
          :style="HoverPositionStyle"
        >
          <div class="grid grid-cols-2 gap-x-2 w-max">
            <span v-if="hoverTimeLabel">
              Time
            </span>
            <span v-if="hoverTimeLabel" class="text-right">
              {{ hoverTimeLabel }}
            </span>
            <span v-if="chartHoverPosition && tooltipData?.distance != null">
              Distance
            </span>
            <span v-if="chartHoverPosition && tooltipData?.distance != null" class="text-right">
              {{ formatValueFor('distance') }}
            </span>
          </div>
        </div>
      </div>

    </div>
  
  </div>
  
</template> 
