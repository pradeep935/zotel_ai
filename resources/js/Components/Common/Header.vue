<script setup>
const { title } = defineProps(['title'])
</script>

<template>
  <div class="page-header row">
    <div class="col-md-6">
      <h3>{{ title }}</h3>
    </div>
    <div class="col-md-6" style="text-align: right;">
      <slot></slot>
    </div>
  </div>
</template>