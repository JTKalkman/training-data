<script setup lang="ts">
import * as L from 'leaflet';
import { onMounted, ref, watch } from 'vue';
import type { RouteDataPoint } from '@/types';
import 'leaflet/dist/leaflet.css';

const props = defineProps<{
  data: Array<RouteDataPoint>;
  markerPosition: RouteDataPoint | null;
}>();

const mapContainer = ref<HTMLElement | null>(null);
let mapInstance: L.Map;
let hoverMarker: L.CircleMarker | null = null;

const createMap = () => {
  const coordinates = props.data.map(point => [point.lat, point.lng]);

  mapInstance = L
    .map(mapContainer.value!, { scrollWheelZoom: false })
    .setView([coordinates[0][0], coordinates[0][1]], 13);
  L
    .tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
    .addTo(mapInstance);
  const polyline = L
    .polyline(coordinates)
    .addTo(mapInstance);
  mapInstance.fitBounds(polyline.getBounds());
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
</script>

<template>
  <div style="width: 100%; height: 300px;" ref="mapContainer"></div>
</template>
