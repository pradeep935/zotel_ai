<script setup>
const { title, show_header = false } = defineProps(['title','show_header'])
</script>

<template>
  <div class="portlet">
    <div class="portlet-head" v-if="show_header">
      <div class="row">
        <div class="col-md-6 col-6">
          <ul class="menu">
            <li class="active mob-hi">
              <a href="#">{{title}}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="portlet-body">
      <slot></slot>
    </div>
  </div>
</template>