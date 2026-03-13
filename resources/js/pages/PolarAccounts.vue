<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import PolarLogo from '@/components/ui/icons/PolarLogo.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, PageProps, PolarProfile } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps<{
  polarProfiles: {
    data: PolarProfile[];
  };
}>()

const flash = computed(() => usePage<PageProps>().props.flash)

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Polar accounts',
    href: route('polar-accounts'),
  },
];

</script>

<template>
  <Head title="Polar accounts" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="p-4">

      <div class="p-4 relative flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border mb-6">
        <div class="mb-3 flex justify-start">
          <PolarLogo class="h-4 w-auto" />
        </div>
        <p class="">
          Connect your Polar account to automatically sync your training sessions. Once linked, 
          your activities will be imported and kept up to date.
        </p>
      </div>

      <ul v-if="polarProfiles.data.length > 0" class="mb-6 space-y-4">
        <li
          class="px-4 py-2 relative flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border" 
          v-for="profile in polarProfiles.data" 
          :key="profile.id"
        >
          <div class="flex justify-between space-x-2 items-end">
            <div>
              <p class="text-sm font-medium mb-1">Account name:</p>
              <p class="break-all font-medium">{{ profile.first_name }} {{ profile.last_name }}</p>
            </div>
            <p class="text-sm" v-if="profile.unlinked_at">Unlinked at {{ profile.unlinked_at }}</p>
            <p class="text-sm" v-else-if="profile.linked_at">Linked at {{ profile.linked_at }}</p>
          </div>
        </li>
      </ul>
    
      <div v-else class="text-center font-medium mb-6">
        <p>No accounts linked yet.</p>
      </div>
    
      <div class="flex justify-end">
        <Button
          variant="default"
          size="default"
          as-child
          class="group cursor-pointer"
         >
          <a :href="route('auth.polar.redirect')">
            Link Polar account
          </a>
        </Button>
      </div>
    
      <div v-if="flash?.error">
        <p class="text-red-500">{{ flash.error }}</p>
      </div>
    
      <div v-if="flash?.success">
        <p class="text-green-500">{{ flash.success }}</p>
      </div>
      
    </div>

  </AppLayout>
</template>
