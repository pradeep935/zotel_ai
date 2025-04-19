<script setup>
import { watchEffect } from 'vue';
const { count } = defineProps(['count'])
watchEffect(() => {
  // runs only once before 3.5
  // re-runs when the "foo" prop changes in 3.5+
  console.log(count)
})

// console.log(count)


</script>

<template>
  
  <div>{{ count }}</div>
</template>