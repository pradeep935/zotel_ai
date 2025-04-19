<script setup>
const { label, error={}, req = false, type="text" } = defineProps(['label','error','req','type'])
const model = defineModel() 
</script>

<template>
    <div class="form-group">
        <label>{{label}} <span class="error" v-if="req">*</span></label>
        <input :type="type" class="form-control" v-model="model" />
        <span class="error" v-if="error.$error">{{ error.$errors[0].$message }}</span>
    </div>
</template>