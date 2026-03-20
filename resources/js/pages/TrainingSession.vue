<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { BreadcrumbItem, TrainingSession as TrainingSessionData, TrainingSessionNavigation } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import TrainingSessionCharts from '@/components/TrainingSessionCharts.vue';
import TrainingSessionMap from '@/components/TrainingSessionMap.vue';
import TrainingSessionFeedback from '@/components/TrainingSessionFeedback.vue';
import TrainingSessionSummary from '@/components/TrainingSessionSummary.vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps<{
  trainingSession: {
    data: TrainingSessionData;
  };
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

</script>

<template>

  <Head :title="props.trainingSession.data.sportType.label" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex justify-between items-center p-4 mb-4">
      <div class="w-28">
        <Link 
          v-if="props.navigation.prev.url" 
          :href="props.navigation.prev.url" 
          class="text-gray-500 dark:text-gray-300 hover:text-foreground transition-colors text-sm flex justify-start items-center px-2 py-1 w-full"
        >
          <ChevronLeft class="h-4" />
          <span>Previous</span>
        </Link>
      </div>
      <div>
        <p class="font-medium">
          <span class="hidden lg:inline">{{props.trainingSession.data.sportType.label}} - </span>
          {{props.trainingSession.data.startedAtHuman}}
        </p>
      </div>
      <div class="w-28">
        <Link 
          v-if="props.navigation.next.url" 
          :href="props.navigation.next.url" 
          class="text-gray-500 dark:text-gray-300 hover:text-foreground transition-colors text-sm flex justify-end items-center px-2 py-1 text-right w-full"
        >
          <span>Next</span>
          <ChevronRight class="h-4" />
        </Link>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row lg:space-x-4 mb-4 px-4">

      <TrainingSessionSummary 
        :class="'lg:mb-0 lg:w-2/3 xl:w-1/2'"
        :trainingSession="trainingSession.data"
      />

      <TrainingSessionFeedback
        :class="'lg:w-1/3 xl:w-1/2'"
      />

    </div>

    <div v-if="trainingSession.data.trainingSummary?.hasRoute" class="mb-4 px-4">
      <TrainingSessionMap :sessionId="trainingSession.data.id" />
    </div>

    <div class="p-4">

      <div class="mb-6 text-center pt-10 pb-10">
        <TrainingSessionCharts :sessionId="trainingSession.data.id" />
      </div>
      
      <!-- <div v-if="TrainingSession.data.heartRateZones.length > 0">
        <ul class="flex space-x-8 mb-6 justify-center">
          <li 
            v-for="zone in TrainingSession.data.heartRateZones" 
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
      </div> -->

    </div>
  </AppLayout>

</template>
