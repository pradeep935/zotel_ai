<script setup>
const { label, error, options, opt_id='value', opt_name='label', req=false   } = defineProps(['label','error','options','opt_id','opt_name','req'])
const model = defineModel() 
</script>

<template>
    <div class="form-group">
        <label>{{label}} <span class="error" v-if="req">*</span></label>
        <select class="form-control" v-model="model">
            <option value="">Select</option>
            <option v-for="opt in options" :value="opt[opt_id]">{{ opt[opt_name] }}</option>
        </select>
    </div>
</template>