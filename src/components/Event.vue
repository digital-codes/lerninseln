<template>

  <ion-list class="events">
    <ion-item v-for="item in items" :key="item.src">
        {{item.src}}
        item.src
    </ion-item>
  </ion-list>


</template>

<script> 

import { 
  IonList, IonItem, 
  } from '@ionic/vue';
import { defineComponent } from 'vue'; 

export default defineComponent({
  components: { 
    IonList, IonItem, 
  },
  data: function() {
    return {
      items: [{
      'text': '2021-06-21 12:00',
      'src': '/assets/img/qr1.png',
      "id":1,
    }, {
      'text': '2021-06-25 10:00',
      'src': '/assets/img/qr2.png',
      "id":2,
    }, {
      'text': '2021-07-05 09:30',
      'src': '/assets/img/qr3.png',
      "id":3,
    }],
    }
  },
  methods:{
    zoom(e) {
      console.log(e)
    }
  }
}); 
</script>

<style scoped>


</style>