<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { BreadcrumbItem, TrainingSession } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import TrainingSessionCharts from '@/components/TrainingSessionCharts.vue';
import TrainingSessionMap from '@/components/TrainingSessionMap.vue';

const props = defineProps<{
  session: {
    data: TrainingSession;
  };
}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Training sessions',
    href: route('training-sessions.week', { year: props.session.data.year, week: props.session.data.week }),
  },
  {
    title: `${props.session.data.sport_type.label} - ${ props.session.data.started_at_human }`
  },
];

</script>

<template>

  <Head title="Training sessions" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">

      <div class="mb-6 text-center pt-10 pb-10">
        <TrainingSessionCharts :sessionId="session.data.id" />
      </div>
  
      <div v-if="session.data.training_summary?.has_route" class="mb-6">
        <TrainingSessionMap :sessionId="session.data.id" />
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
  </AppLayout>

</template>
