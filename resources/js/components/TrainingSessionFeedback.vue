<script setup lang="ts">
import { cn } from '@/lib/utils';
import { HTMLAttributes } from 'vue';
import TrainingSessionRating from './TrainingSessionRating.vue';
import TrainingSessionNotes from './TrainingSessionNotes.vue';

const props = defineProps<{
  class?: HTMLAttributes["class"];
  trainingSessionId: string;
  notes?: string;
  rating?: number;
}>();
</script>

<template>
  <div :class="cn('border rounded-xl p-4 flex flex-col', props.class)">
    <div class="flex flex-col sm:flex-row sm:justify-between lg:flex-col xl:flex-row">
      <h3 class="font-medium mb-3">Feedback</h3>
      
      <TrainingSessionRating 
        :trainingSessionId="props.trainingSessionId"
        :rating="props.rating"
      />
      
    </div>

    <TrainingSessionNotes
      :trainingSessionId="props.trainingSessionId"
      :notes="props.notes"
      :class="'grow'"
    />

  </div>
</template>
