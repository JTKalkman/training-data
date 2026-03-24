<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouteData } from '@/composables/useMapData';
import Map from './Map.vue';
import Spinner from './ui/spinner/Spinner.vue';

const props = defineProps<{
  sessionId: string;
}>();

const { data, loading, error, fetch } = useRouteData(props.sessionId);

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
      <Map :data="data" />
    </div>

  </div>
</template>
