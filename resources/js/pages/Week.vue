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
        <ul>
          <li v-for="session in trainingSessions?.data" :key="session.id" class="border p-2 my-1">
  
            <div>
              <Link :href="route('training-sessions.session', { session: session.id })" class="underline">
                {{ session.sportType.label }} — {{ session.startedAtHuman }}
              </Link>
            </div>
  
            <div v-if="session.durationHuman">
              {{ session.durationHuman }}
            </div>
  
          </li>
        </ul>
      </div>
  
    </div>
  </AppLayout>

</template>
