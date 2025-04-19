<script setup>
const { label, error, options, opt_id='value', opt_name='label' } = defineProps(['label','error','options','opt_id','opt_name'])
const model = defineModel() 

function addItem(item) {
    var idx = model.value.indexOf(item);
    if (idx == -1) {
        model.value.push(item);
    } else {
        model.value.splice(idx, 1);
    }
}

</script>

<template>
    <div class="form-group">
        <label>{{label}}</label>
        <div v-for="opt in options">
            <input type="checkbox" @click="addItem(opt[opt_id])" :checked="model.indexOf(opt[opt_id]) > -1" /> <label>{{ opt[opt_name] }}</label>
        </div>
    </div>
</template>