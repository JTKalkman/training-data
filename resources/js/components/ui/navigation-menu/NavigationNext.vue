<script setup lang="ts">
  import { Link } from '@inertiajs/vue3';
  import { ChevronRight } from 'lucide-vue-next';

  const props = defineProps<{
    url: string;
  }>()
</script>

<template>
  <Link 
    :href="props.url" 
    class="text-gray-500 dark:text-gray-300 hover:text-foreground transition-colors text-sm flex justify-end items-center px-2 py-1 text-right w-full"
  >
    <span>Next</span>
    <ChevronRight class="h-4" />
  </Link>
</template>
