<script setup lang="ts">
  import { Link } from '@inertiajs/vue3';
  import { ChevronLeft } from 'lucide-vue-next';

  const props = defineProps<{
    url: string;
  }>()
</script>

<template>
  <Link 
    :href="props.url"
    class="text-gray-500 dark:text-gray-300 hover:text-foreground transition-colors text-sm flex justify-start items-center px-2 py-1 w-full"
  >
    <ChevronLeft class="h-4" />
    <span>Previous</span>
  </Link>
</template>
