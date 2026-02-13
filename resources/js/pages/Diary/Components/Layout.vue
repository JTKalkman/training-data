<script setup>
defineProps({
  title: String,
})
</script>

<template>
  <div>
    <header class="bg-gray-800 text-white p-4">
      <h1 class="font-bold">Training Diary</h1>
    </header>

    <main class="max-w-4xl mx-auto p-4">
      <slot />
    </main>
  </div>
</template>
