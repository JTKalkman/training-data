<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { Annoyed, Frown, Laugh, Meh, Smile } from 'lucide-vue-next';
import { onBeforeUnmount } from 'vue';
import { route } from 'ziggy-js';

const ratingOptions = [
  { value: 1, icon: Frown, color: 'text-red-500', hoverColor: 'hover:text-red-500' },
  { value: 2, icon: Annoyed, color: 'text-orange-500', hoverColor: 'hover:text-orange-500' },
  { value: 3, icon: Meh, color: 'text-yellow-500', hoverColor: 'hover:text-yellow-500' },
  { value: 4, icon: Smile, color: 'text-green-500', hoverColor: 'hover:text-green-500' },
  { value: 5, icon: Laugh, color: 'text-green-400', hoverColor: 'hover:text-green-400' },
];

const props = defineProps<{
  trainingSessionId: string;
  rating?: number;
}>();

const form = useForm({
  rating: props.rating || null,
});

const submit = () => {
  form.patch(route('training-sessions.update', props.trainingSessionId), {
    only: ['trainingSession'],
    preserveState: true,
    preserveScroll: true,
  });
}

const removeListener = router.on('before', (event) => {
  if (form.processing) {
    event.preventDefault()
  }
})

onBeforeUnmount(() => removeListener())
</script>

<template>
  <div class="flex space-x-2 mb-3 justify-end">
    <div v-for="option in ratingOptions" :key="option.value">
      <input
        type="radio"
        :id="`rating-${option.value}`"
        name="rating"
        :value="option.value"
        class="hidden"
        v-model="form.rating"
        :disabled="form.processing"
        @change="submit"
      />
      <label :for="`rating-${option.value}`">
        <component
          :is="option.icon"
          class="w-6 cursor-pointer"
          :class="[
            form.rating === option.value ? option.color : `text-gray-300 ${option.hoverColor}`,
            form.processing ? 'opacity-50 pointer-events-none' : ''
          ]"
        />
      </label>
    </div>
  </div>
</template>
