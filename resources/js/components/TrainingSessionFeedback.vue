<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Form, router, useForm } from '@inertiajs/vue3';
import { Annoyed, Frown, Laugh, Meh, Smile } from 'lucide-vue-next';
import { HTMLAttributes } from 'vue';
import { route } from 'ziggy-js';

const ratingOptions = [
  { value: 1, icon: Frown, color: 'text-red-500'},
  { value: 2, icon: Annoyed, color: 'text-orange-500'},
  { value: 3, icon: Meh, color: 'text-yellow-500'},
  { value: 4, icon: Smile, color: 'text-green-500'},
  { value: 5, icon: Laugh, color: 'text-green-400'},
]

const props = defineProps<{
  class?: HTMLAttributes["class"];
  trainingSessionId: string;
  notes?: string;
  rating?: number;
}>()

const form = useForm({
  rating: props.rating || null,
  notes: props.notes || null,
})

const submit = () => {
  form.patch(route('training-sessions.update', props.trainingSessionId), {
    only: ['trainingSession'],
    preserveState: true,
  })
}
</script>

<template>
  <div :class="cn('border rounded-xl p-4', props.class)">
    <Form 
      @change="submit"
    >
      <div class="flex flex-col sm:flex-row sm:justify-between lg:flex-col xl:flex-row">
        <h3 class="font-medium mb-3">Feedback</h3>
  
        <fieldset class="flex space-x-2 mb-3 justify-end">

          <div v-for="option in ratingOptions" :key="option.value">
            <input
              type="radio"
              :id="`rating-${option.value}`"
              name="rating"
              :value="option.value"
              class="hidden"
              v-model="form.rating"
              :disabled="form.processing"
            />
            <label :for="`rating-${option.value}`">
              <component
                :is="option.icon"
                class="w-6 cursor-pointer"
                :class="[
                  form.rating === option.value ? option.color : `text-gray-300 hover:${option.color}`,
                  form.processing ? 'opacity-50 pointer-events-none' : ''
                ]"
              />
            </label>
          </div>
        </fieldset>
      </div>
    </Form>

    <div>
      <p class="overflow-y-scroll max-h-30">
        <span class="text-xs text-gray-400">Additional notes...</span>
      </p>
    </div>
  </div>
</template>
