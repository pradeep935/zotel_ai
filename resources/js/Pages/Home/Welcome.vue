<script>
  import { ref } from 'vue';

  export default {
    setup() {
      const count = ref(0)

      // expose the ref to the template
      return {
        count
      }
    }
  }
</script>

<template>
  <div>
    <h1>Welcome to Laravel 11 with Inertia.js and Vue.jsssss</h1>
    
    <button @click="count++">You clicked me {{ count }} times.</button>

    <Hello :count="count" />
  </div>
</template>

