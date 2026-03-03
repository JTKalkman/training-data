<script setup lang="ts">
import { RouteDataPoint } from '@/types';
import { onMounted, ref } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps<{
  data: Array<RouteDataPoint>;
}>();

const mapContainer = ref<HTMLElement | null>(null);
let mapInstance: L.Map;

const createMap = () => {
  // const mapData = props.data.map(d => d)
  const coordinates = props.data.map(point => [point.lat, point.lng]);

  mapInstance = L.map(mapContainer.value!).setView([coordinates[0][0], coordinates[0][1]], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapInstance);
  const polyline = L.polyline(coordinates).addTo(mapInstance);
  mapInstance.fitBounds(polyline.getBounds());
}

onMounted(() => {
  createMap();
})
</script>

<template>
  <div style="width: 100%; height: 300px;" ref="mapContainer"></div>
</template>
