<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({
  title: String,
})
</script>

<template>
  <div>
    <header class="bg-gray-800 text-white p-4 flex justify-between">
      <h1 class="font-bold">
        <Link href="/">
          Training Diary
        </Link>
      </h1>
      
      <Link href="/account/settings">
        Account settings
      </Link>
    </header>

    <main class="max-w-4xl mx-auto p-4">
      <slot />
    </main>
  </div>
</template>
