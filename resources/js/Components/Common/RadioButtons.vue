<script setup>
const { label, error, options, opt_id='value', opt_name='label' } = defineProps(['label','error','options','opt_id','opt_name'])
const model = defineModel()

</script>

<template>
    <div class="form-group">
        <label>{{label}}</label>
        <div v-for="opt in options">
            <input type="radio" v-model="model" :value="opt[opt_id]" /> <label>{{ opt[opt_name] }}</label>
        </div>
    </div>
</template>