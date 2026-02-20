<script setup>
import { computed } from 'vue';
import Layout from './../Components/Layout.vue';
import { usePage } from '@inertiajs/vue3';

defineOptions({ layout: Layout })

const flash = computed(() => usePage().props.flash)

</script>

<template>
  <a href="/auth/polar/redirect">Link Polar account</a>

  <div v-if="flash.error">
    <p class="text-red-500">{{ flash.error }}</p>
  </div>

  <div v-if="flash.success">
    <p class="text-green-500">{{ flash.success }}</p>
  </div>

</template>
