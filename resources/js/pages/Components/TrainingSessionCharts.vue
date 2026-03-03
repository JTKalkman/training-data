<script setup lang="ts">
import { useSampleData } from '@/composables/useSampleData';
import { computed, onMounted, ref } from 'vue';
import Spinner from './Spinner.vue';
import TrainingSessionChart from './TrainingSessionChart.vue';
import { SampleDataPoint } from '@/types/sample-data-point';
import { HoverPosition } from '@/types';

const props = defineProps<{
  sessionId: string;
}>();

const { data, loading, error, fetch } = useSampleData(props.sessionId);

const fields = ['heart_rate', 'speed', 'cadence', 'altitude', 'pace'] as const;
const availableFields = computed(() => {
  if (!data.value?.length) return [];

  const first = data.value[0];
  return fields.filter(field => first[field] !== undefined);
});

const chartData = computed(() => {
  if (!data.value?.length) return {
    heart_rate: [],
    speed: [],
    cadence: [],
    altitude: [],
    pace: [],
  };

  return {
    heart_rate: data.value.map(row => ({ x: row.time, y: row.heart_rate })),
    speed: data.value
      .filter((row): row is SampleDataPoint & { speed: number } => row.speed !== undefined)
      .map(row => ({ x: row.time, y: row.speed })),
    pace: data.value
        .filter((row): row is SampleDataPoint & { pace: number } => row.pace !== undefined)
        .map(row => ({ x: row.time, y: row.pace })),
    cadence: data.value
      .filter((row): row is SampleDataPoint & { cadence: number } => row.cadence !== undefined)
      .map(row => ({ x: row.time, y: row.cadence })),
    altitude: data.value
      .filter((row): row is SampleDataPoint & { altitude: number } => row.altitude !== undefined)
      .map(row => ({ x: row.time, y: row.altitude })),
  };
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
  <div v-if="loading" class="flex justify-center bg-gray-400 pt-10 pb-10">
    <Spinner />
  </div>
  
  <div v-if="error">
    <p class="text-center text-red-500 text-sm">
      {{ error }}
    </p>
  </div>
  
  <TrainingSessionChart 
    v-for="field in availableFields"
    :field="field"
    :data="chartData[field]"
    :chartHoverPosition="chartHoverPosition"
    :hoverSource="hoverSource"
    @hover="handleChartHover"
  />
  
</template> 
