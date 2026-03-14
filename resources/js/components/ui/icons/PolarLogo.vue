<script setup lang="ts">
import { cn } from "@/lib/utils";
import type { HTMLAttributes } from "vue"

const props = defineProps<{
  class?: HTMLAttributes["class"]
}>()
</script>

<template>
  <img 
    src="/images/polar-logo-black.svg" 
    :class="cn('dark:hidden', props.class,)"
  />
  <img 
    src="/images/polar-logo-white.svg" 
    :class="cn('hidden dark:block', props.class)"
  />
</template>
