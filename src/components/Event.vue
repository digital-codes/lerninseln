<template>
  <div>
  <h2>{{date}}</h2>
  <p>{{text}}</p>
  </div>

</template>

<script> 

import { defineComponent } from 'vue'; 

export default defineComponent({
  props: ['text',"date","id"]
});

</script>

<style scoped>

</style>

