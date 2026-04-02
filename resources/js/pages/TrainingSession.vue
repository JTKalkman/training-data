<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import TrainingSessionCharts from '@/components/TrainingSessionCharts.vue';
import TrainingSessionFeedback from '@/components/TrainingSessionFeedback.vue';
import TrainingSessionMap from '@/components/TrainingSessionMap.vue';
import TrainingSessionSummary from '@/components/TrainingSessionSummary.vue';
import NavigationNext from '@/components/ui/navigation-menu/NavigationNext.vue';
import NavigationPrevious from '@/components/ui/navigation-menu/NavigationPrevious.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, runningPaceZone, TrainingSession as TrainingSessionData, TrainingSessionNavigation } from '@/types';

const props = defineProps<{
  trainingSession: {
    data: TrainingSessionData;
  };
  runningPaceZones: runningPaceZone[];
  navigation: TrainingSessionNavigation;
}>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Training sessions',
    href: route('training-sessions.week', { year: props.trainingSession.data.year, week: props.trainingSession.data.week }),
  },
  {
    title: `${props.trainingSession.data.sportType.label}`
  },
];

const fields = [
  'heart_rate', 
  (props.trainingSession.data.sportType.name === 'running' ? 'pace' : 'speed'), 
  'cadence', 
  'altitude'
];

</script>

<template>

  <Head :title="props.trainingSession.data.sportType.label" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex justify-between items-center p-4 mb-4">
      <div class="w-28">
        <NavigationPrevious
          v-if="props.navigation.prev.url"
          :url="props.navigation.prev.url"
        />
      </div>
      <div>
        <p class="font-medium">
          <span class="hidden lg:inline">{{props.trainingSession.data.sportType.label}} - </span>
          {{props.trainingSession.data.startedAtHuman}}
        </p>
      </div>
      <div class="w-28">
        <NavigationNext
          v-if="props.navigation.next.url"
          :url="props.navigation.next.url"
        />
      </div>
    </div>

    <div class="flex flex-col lg:flex-row lg:space-x-4 mb-4 px-4">

      <TrainingSessionSummary 
        :class="'lg:mb-0 lg:w-2/3 xl:w-1/2'"
        :trainingSession="trainingSession.data"
      />

      <TrainingSessionFeedback
        :class="'lg:w-1/3 xl:w-1/2'"
        :trainingSessionId="trainingSession.data.id"
        :notes="trainingSession.data.notes"
        :rating="trainingSession.data.rating"
      />

    </div>

    <div v-if="trainingSession.data.trainingSummary?.hasRoute" class="mb-4 px-4">
      <TrainingSessionMap :sessionId="trainingSession.data.id" />
    </div>

    <div class="px-4 mb-16">
      <TrainingSessionCharts 
        :sessionId="trainingSession.data.id" 
        :heartRateZones="trainingSession.data.heartRateZones"
        :fields="fields"
      />
    </div>
  </AppLayout>

</template>
