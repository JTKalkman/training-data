<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Layout from './../Components/Layout.vue';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { TrainingSession, WeekNavigation } from '@/types';

defineOptions({ layout: Layout })

const props = defineProps<{
  trainingSessions?: {
    data: TrainingSession[]
  },
  year: number,
  week: number,
  navigation: WeekNavigation
}>()

const hasSessions = computed(() => props.trainingSessions?.data.length ?? 0 > 0);
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
        <li v-for="session in trainingSessions?.data" :key="session.id" class="border p-2 my-1">

          <div>
            <Link :href="route('sessions.session', { session: session.id })" class="underline">
              {{ session.sport_type.label }} — {{ session.started_at_human }}
            </Link>
          </div>

          <div v-if="session.duration_human">
            {{ session.duration_human }}
          </div>

        </li>
      </ul>
    </div>

  </div>
</template>
