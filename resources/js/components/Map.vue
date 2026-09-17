<script setup lang="ts">
import * as L from 'leaflet';
import { onMounted, onUnmounted, ref, watch } from 'vue';
import type { RouteDataPoint } from '@/types';
import 'leaflet/dist/leaflet.css';
import Button from './ui/button/Button.vue';

const props = defineProps<{
  data: Array<RouteDataPoint>;
  markerPosition: RouteDataPoint | null;
}>();

const mapContainer = ref<HTMLElement | null>(null);
let mapInstance: L.Map;
let polyline: L.Polyline;
let hoverMarker: L.CircleMarker | null = null;

const getCoordinates = (): L.LatLngExpression[] => {
  return props.data.map(point => [point.lat, point.lng]);
}

const createMap = () => {
  const coordinates = getCoordinates();

  mapInstance = L.map(mapContainer.value!, {
    scrollWheelZoom: false,
    zoomSnap: 0.1,
    zoomControl: false,
  });

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapInstance);

  polyline = L.polyline(coordinates).addTo(mapInstance);

  recenter()
}

const recenter = () => {
  mapInstance.fitBounds(polyline.getBounds(), { padding: [20, 20], maxZoom: 15 });
}

watch(() => props.markerPosition, (position) => {
  if (!position) {
    hoverMarker?.remove();
    hoverMarker = null;
    return;
  }

  if (!hoverMarker) {
    hoverMarker = L.circleMarker([position.lat, position.lng], {
      radius: 6,
      fillColor: '#3388ff',
      fillOpacity: 1,
      weight: 2,
      color: '#fafafa',
      opacity: 1,
    }).addTo(mapInstance)
  } else {
    hoverMarker.setLatLng([position.lat, position.lng])
  }
})

onMounted(() => createMap())

onUnmounted(() => {
  mapInstance?.remove();
});

</script>

<template>
  <div class="relative">
    <div style="width: 100%; height: 300px;" ref="mapContainer"></div>

    <div class="absolute top-2 right-2 z-1000 flex flex-col gap-1">

      <div class="bg-gray-400 rounded-lg">
        <Button
          variant="secondary"
          type="button"
          title="Zoom in"
          @click="mapInstance.zoomIn()"
          class="w-8 h-8 p-0 font-medium text-base border border-gray-400 dark:border-gray-800 shadow"
        >+</Button>
      </div>

      <div class="bg-gray-400 rounded-lg">
        <Button
          variant="secondary"
          type="button"
          title="Zoom out"
          @click="mapInstance.zoomOut()"
          class="w-8 h-8 p-0 font-medium text-base border border-gray-400 dark:border-gray-800 shadow"
          >-</Button>
      </div>

      <div class="bg-gray-400 rounded-lg">
        <Button
          variant="secondary"
          type="button"
          title="Recenter"
          @click="recenter"
          class="w-8 h-8 p-0 font-medium text-base border border-gray-400 dark:border-gray-800 shadow"
        >⟲</Button>
      </div>

    </div>
  </div>
</template>
