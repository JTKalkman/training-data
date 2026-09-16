<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useRouteData } from '@/composables/useMapData';
import Map from './Map.vue';
import Spinner from './ui/spinner/Spinner.vue';
import { HoverPosition } from '@/types/hover-position.js';
import { RouteDataPoint } from '@/types/route-data-point.js';

const props = defineProps<{
  sessionId: string;
  hoverPosition: HoverPosition | null;
}>();

const { data, loading, error, fetch } = useRouteData(props.sessionId);

function findNearestByTime(points: Array<RouteDataPoint>, targetTime: number): RouteDataPoint {
  let lo = 0, hi = points.length -1;

  while (lo < hi) {
    const mid = (lo + hi) >> 1;
    if (points[mid].time < targetTime) lo = mid + 1;
    else hi = mid;
  }

  if (lo > 0 && Math.abs(points[lo - 1].time - targetTime) < Math.abs(points[lo].time - targetTime)) {
    return points[lo - 1];
  }

  return points[lo];
}

const hoverMarkerPosition = computed((): RouteDataPoint | null => {
  if (!props.hoverPosition || !data.value?.length) return null;

  const point = findNearestByTime(data.value, props.hoverPosition.time);

  return point ? {
    time: point.time,
    time_label: point.time_label,
    lat: point.lat,
    lng: point.lng
  } : null;
})

onMounted(() => {
  fetch();
})

</script>

<template>
  <div class="border rounded-xl">

    <div v-if="loading" class="flex justify-center pt-10 pb-10">
      <Spinner />
    </div>

    <div v-if="error">
      <p class="text-center text-red-500 text-sm">
        {{ error }}
      </p>
    </div>

    <div v-if="data" class="rounded-lg overflow-hidden">
      <Map :data="data" :markerPosition="hoverMarkerPosition" />
    </div>

  </div>
</template>
