<script setup>
const { type="submit", disabled=false, processing=false } = defineProps(['type','disabled','processing'])
</script>

<template>
  <button class="btn btn-info" :type="type" :disabled="disabled || processing">
    <slot></slot>
    <span v-if="processing" class="spinner-border text-white spinner-border-sm" role="status" style="margin-left: 5px"></span>
  </button>
</template>