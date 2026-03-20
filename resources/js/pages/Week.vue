<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { BreadcrumbItem, TrainingSession, WeekNavigation } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Navigation } from 'lucide-vue-next';
import NavigationPrevious from '@/components/ui/navigation-menu/NavigationPrevious.vue';
import NavigationNext from '@/components/ui/navigation-menu/NavigationNext.vue';

const props = defineProps<{
  trainingSessions?: {
    data: TrainingSession[];
  };
  year: number;
  week: number;
  navigation: WeekNavigation;
}>()

const hasSessions = computed(() => props.trainingSessions?.data.length ?? 0 > 0);

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Training sessions',
    href: route('training-sessions'),
  },
];

</script>

<template>
  <Head title="Training sessions" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="flex justify-between mb-4">

        <div>
          <NavigationPrevious
            v-if="navigation.prev.url"
            :url="navigation.prev.url"
          />
        </div>

        <h1 class="font-medium mb-4">
          Week {{ week }} - {{ year }}
        </h1>

        <div>
          <NavigationNext
            v-if="navigation.next.url"
            :url="navigation.next.url"
          />
        </div>

      </div>
  
      <div v-if="!hasSessions">
        <p class="text-gray-500 text-center pt-20 pb-20">No training sessions this week.</p>
      </div>

      <div v-else>
        <ul class="border rounded-xl">
          <li 
            v-for="session in trainingSessions?.data" :key="session.id" 
            class="relative p-4 border-b first:rounded-t-xl last:border-0 last:rounded-b-xl hover:bg-sidebar-accent dark:hover:bg-sidebar-accent flex items-center"
          >

            <div class="bg-red-700 rounded-lg w-12 h-12 mr-4"></div>

            <div class="lg:flex-1">
              <div class="grid grid-cols-1 lg:grid-cols-3 lg:items-center w-full">
                <p class="font-medium lg:w-1/3 text-nowrap">{{ session.sportType.label }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 lg:w-1/3 text-nowrap">
                  {{ session.startedAtHuman }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 lg:w-1/3 text-nowrap">
                  <span v-if="session.durationHuman">{{ session.durationHuman }}</span>
                </p>
              </div>

              <Link :href="route('training-sessions.session', { session: session.id })" class="after:absolute after:inset-0">
                <span class="sr-only">View details for {{ session.sportType.label }} session on {{ session.startedAtHuman }}</span>
              </Link>
            </div>

          </li>
        </ul>
      </div>
  
    </div>
  </AppLayout>

</template>
