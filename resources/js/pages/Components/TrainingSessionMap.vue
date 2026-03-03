<script setup lang="ts">
import { useRouteData } from '@/composables/useMapData';
import { onMounted } from 'vue';
import Spinner from './Spinner.vue';
import Map from './Map.vue';

const props = defineProps<{
  sessionId: string;
}>();

const { data, loading, error, fetch } = useRouteData(props.sessionId);

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

  <div v-if="data">
    <Map :data="data" />
  </div>

</template>
