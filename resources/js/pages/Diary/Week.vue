<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from './Components/Layout.vue';
import { computed } from 'vue';

defineOptions({ layout: Layout })

const props = defineProps({
  trainingSessions: Object,
  year: Number,
  week: Number,
  navigation: Object
})

const hasSessions = computed(() => props.trainingSessions.data.length > 0);
</script>

<template>
  <div class="p-4">
    <div class="flex justify-between px-4">
      <Link :href="navigation.prev.url">Previous</Link>
      <h1 class="text-xl font-bold mb-4">
        Week {{ week }} - {{ year }}
      </h1>
      <Link :href="navigation.next.url">Next</Link>
    </div>

    <div v-if="!hasSessions">
      <p class="text-gray-500 text-center pt-20 pb-20">No training sessions this week.</p>
    </div>
  
    <div v-else>
      <ul>
        <li v-for="session in trainingSessions.data" :key="session.id" class="border p-2 my-1">
          <div>{{ session.sport_type.label }}</div>
          <div>{{ session.started_at }}</div>
          <div v-if="session.training_summary">
            HR min: {{ session.training_summary.min_heart_rate }}<br/>
            HR avg: {{ session.training_summary.avg_heart_rate }}<br/>
            HR max: {{ session.training_summary.max_heart_rate }}
          </div>
        </li>
      </ul>
    </div>

  </div>
</template>
