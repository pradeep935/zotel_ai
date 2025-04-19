<script setup>
const { amount } = defineProps(['amount'])
</script>

<template>
  <span>
    {{ new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(amount) }}
  </span>
</template>