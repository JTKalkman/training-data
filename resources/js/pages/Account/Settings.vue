<script setup lang="ts">
import { computed } from 'vue';
import Layout from './../Components/Layout.vue';
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { PageProps, PolarProfile } from '@/types';

defineOptions({ layout: Layout })

const props = defineProps<{
  polar_profiles: {
    data: PolarProfile[]
  }
}>()

const flash = computed(() => usePage<PageProps>().props.flash)

</script>

<template>

  <h2 class="font-semibold mb-6">Linked accounts</h2>

  <ul v-if="polar_profiles.data.length > 0" class="mb-6 space-y-4">
    <li
      class="border rounded px-4 py-2" 
      v-for="profile in polar_profiles.data" 
      :key="profile.id"
    >
      <p>Polar</p>
      <div class="flex justify-between space-x-2 items-end">
        <p class="break-all">{{ profile.first_name }} {{ profile.last_name }}</p>
        <p v-if="profile.unlinked_at">Unlinked at {{ profile.unlinked_at }}</p>
        <p v-else-if="profile.linked_at">Linked at {{ profile.linked_at }}</p>
      </div>
    </li>
  </ul>

  <div v-else class="text-center font-medium mb-6">
    <p>No accounts linked yet.</p>
  </div>

  <div class="flex justify-end">
    <a 
      class="
        bg-blue-600 rounded px-4 py-2 font-medium
        hover:bg-blue-700
      "
      :href="route('auth.polar.redirect')"
    >
      Link Polar account
    </a>
  </div>

  <div v-if="flash?.error">
    <p class="text-red-500">{{ flash.error }}</p>
  </div>

  <div v-if="flash?.success">
    <p class="text-green-500">{{ flash.success }}</p>
  </div>

</template>
