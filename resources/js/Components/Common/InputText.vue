<script setup>
const { label, error, req = false, type="text" } = defineProps(['label','error','req','type'])
const model = defineModel() 
</script>

<template>
    <div class="form-group">
        <label>{{label}} <span class="error" v-if="req">*</span></label>
        <textarea class="form-control" v-model="model" ></textarea>
    </div>
</template>