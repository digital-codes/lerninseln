<template>

    <ion-card class="w">
    <ion-card-header>
      <ion-card-title>Reservieren</ion-card-title>
      <ion-card-subtitle>Click item so select</ion-card-subtitle>
      <ion-card-subtitle>Slide left to Show Codes</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>

    <Events></Events>

    </ion-card-content>
  </ion-card>

</template>

<script> 

import { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
import { defineComponent } from 'vue';
import Events from '@/components/Events.vue';

export default defineComponent({
  components: { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, Events },
}); 
</script>

<style scoped>
.w {
  width:100%;
}

</style>