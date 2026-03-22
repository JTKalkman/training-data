<script setup lang="ts">
import { cn } from '@/lib/utils';
import { router, useForm } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { computed, HTMLAttributes, onBeforeUnmount, onMounted } from 'vue';
import { route } from 'ziggy-js';
import Button from './ui/button/Button.vue';
import TextArea from './ui/text-area/TextArea.vue';

const props = defineProps<{
  class?: HTMLAttributes["class"];
  trainingSessionId: string;
  notes?: string | number | null;
}>();

const notesChanged = computed(() => (form.notes ?? '') !== (props.notes ?? ''))

const form = useForm({
  notes: props.notes || null,
});

const cancel = () => {
  form.reset('notes')
}

const submit = () => {
  form.patch(route('training-sessions.update', props.trainingSessionId), {
    only: ['trainingSession'],
    preserveState: true,
  });
}

const handleChange = (value: string | number | null) => {
  form.notes = value
}

const removeListener = router.on('before', (event) => {
  if (form.processing) {
    event.preventDefault()
  }
})

onBeforeUnmount(() => removeListener())
</script>

<template>
  <form
    @submit.prevent="submit"
    @keydown.esc="cancel"
    :class="cn('flex flex-col space-y-2', props.class)"
  >
 
    <TextArea 
      placeholder="Additional notes..."
      :modelValue="form.notes"
      :disabled="form.processing"
      name="notes"
      id="notes"
      class="min-h-10 lg:grow"
      v-on:update:modelValue="handleChange"
    />

    <div v-if="notesChanged" class="space-x-2 flex justify-end mt-1">
      <Button 
        :variant="'secondary'"
        :size="'sm'"
        type="button" 
        @click="cancel"
      >
        <X />
      </Button>
      <Button 
        type="submit" 
        :size="'sm'"
        :disabled="form.processing"
      >
        <Check />
      </Button>
    </div>
  </form>
</template>
