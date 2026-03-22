<script lang="ts" setup>
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import type { TrainingSession as TrainingSessionData } from '@/types';

const props = defineProps<{
  class?: HTMLAttributes["class"];
  trainingSession: TrainingSessionData
}>()
</script>

<template>
  <div :class="cn('border rounded-xl p-4 mb-4', props.class)">

    <h3 class="font-medium mb-3">Training summary</h3>
    
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
      <div>
        <p class="text-sm text-gray-600 dark:text-gray-400">Duration:</p>
        <p>{{ trainingSession.durationHuman }}</p>
      </div>

      <div>
        <p class="text-sm text-gray-600 dark:text-gray-400">Heartrate:</p>
        <p class="flex space-x-4">
          <span>
            <span class="text-sm">Min:</span> 
            {{ trainingSession.trainingSummary?.minHeartRate }}
          </span>
          <span>
            <span class="text-sm">Avg:</span> 
            {{ trainingSession.trainingSummary?.avgHeartRate }}
          </span>
          <span>
            <span class="text-sm">Max:</span> 
            {{ trainingSession.trainingSummary?.maxHeartRate }}
          </span>
        </p>
      </div>

      <div>
        <p class="text-sm text-gray-600 dark:text-gray-400">Calories:</p>
        <p>{{ trainingSession.trainingSummary?.calories }} <span class="text-sm">kcal</span></p>
      </div>

      <div v-if="trainingSession.trainingSummary?.distance">
        <p class="text-sm text-gray-600 dark:text-gray-400">Distance:</p>
        <p>{{ trainingSession.trainingSummary?.distance }} <span class="text-sm">km</span></p>
      </div>
    </div>

  </div>
</template>
