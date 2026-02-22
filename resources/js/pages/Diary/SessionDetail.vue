<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from './../Components/Layout.vue';
import SessionChart from './../Components/SessionChart.vue';

defineOptions({ layout: Layout })

const props = defineProps({
  session: Object
})

</script>

<template>
  <div class="p-4">

    <div class="flex flex-col px-4 mb-6">
      <Link :href="route('sessions.week', { year: session.data.year, week: session.data.week })">← Back</Link>
      <div class="flex justify-center">
        <h1 class="text-xl font-bold mb-4">
          {{ session.data.sport_type.label }} - 
          {{ session.data.started_at_human }}
        </h1>
      </div>
    </div>

    <div class="mb-6 text-center pt-10 pb-10">
      <SessionChart :sessionId="session.data.id"></SessionChart>
    </div>
    
    <div v-if="session.data.heart_rate_zones.length > 0">
      <ul class="flex space-x-8 mb-6 justify-center">
        <li 
          v-for="zone in session.data.heart_rate_zones" 
          :key="zone.id"
          class="flex space-x-2"
        >
          <span class="text-xs font-bold rounded px-2 py-1"
          :class="{
            ' bg-blue-200 text-blue-600': zone.color === 'blue',
            ' bg-green-200 text-green-600': zone.color === 'green',
            ' bg-yellow-200 text-yellow-600': zone.color === 'yellow',
            ' bg-orange-200 text-orange-600': zone.color === 'orange',
            ' bg-red-200 text-red-600': zone.color === 'red',
          }">
            {{ zone.name }}
          </span>
          <span class="text-sm">{{ zone.min_bpm }} - {{ zone.max_bpm }}</span>
        </li>
      </ul>
    </div>

    <div v-if="session.data.training_summary" class="flex gap-4 justify-center mb-4">
      <span>
        HR min: {{ session.data.training_summary.min_heart_rate }}
      </span>
      <span>
        HR avg: {{ session.data.training_summary.avg_heart_rate }}
      </span>
      <span>
        HR max: {{ session.data.training_summary.max_heart_rate }}
      </span>
    </div>

    <div class="text-center">
      Duration: {{ session.data.duration_human }}
    </div>

  </div>
</template>
