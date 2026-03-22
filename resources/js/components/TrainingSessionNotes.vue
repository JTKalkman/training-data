<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Form, router, useForm } from '@inertiajs/vue3';
import { computed, HTMLAttributes, onBeforeUnmount, onMounted } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps<{
  class?: HTMLAttributes["class"];
  trainingSessionId: string;
  notes?: string;
}>();

const notesChanged = computed(() => form.notes !== props.notes)

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

onMounted(() => {
  const removeListener = router.on('before', (event) => {
    if (form.processing) {
      event.preventDefault()
    }
  })

  onBeforeUnmount(() => removeListener())
})
</script>

<template>
  <Form
    @submit.prevent="submit"
    @keydown.esc="cancel"
    :class="cn('flex flex-col space-y-2', props.class)"
  >
    <textarea
      v-model="form.notes"
      placeholder="Additional notes..."
      class="border rounded-sm text-sm overflow-y-scroll min-h-10 p-2 lg:grow mb-0"
      :disabled="form.processing"
      name="notes"
      id="notes"
    />
    <div v-if="notesChanged">
      <button class="bg-primary" type="submit" :disabled="form.processing">Save</button>
      <button class="bg-gray-500" type="reset" @click="cancel"></button>
    </div>
  </Form>
</template>
