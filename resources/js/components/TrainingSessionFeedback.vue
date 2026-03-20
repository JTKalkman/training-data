<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Annoyed, Frown, Laugh, Meh, Smile } from 'lucide-vue-next';
import { HTMLAttributes } from 'vue';

const props = defineProps<{
  class?: HTMLAttributes["class"];
}>()
</script>

<template>
  <div :class="cn('border rounded-xl p-4', props.class)">
    <div class="flex flex-col sm:flex-row sm:justify-between lg:flex-col xl:flex-row">
      <h3 class="font-medium mb-3">Feedback</h3>

      <div class="flex space-x-2 mb-3 justify-end">
        <Frown   class="w-6 text-gray-300 hover:text-red-500"/>
        <Annoyed class="w-6 text-gray-300 hover:text-orange-500"/>
        <Meh     class="w-6 text-gray-300 hover:text-yellow-500"/>
        <Smile   class="w-6 text-gray-300 hover:text-green-500"/>
        <Laugh   class="w-6 text-gray-300 hover:text-green-400"/>
      </div>
    </div>

    <div>
      <p class="overflow-y-scroll max-h-30">
        <span class="text-xs text-gray-400">Additional notes...</span>
      </p>
    </div>
  </div>
</template>
