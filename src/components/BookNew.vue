<template>

    <ion-card class="w">
    <ion-card-header>
      <ion-card-title>Reservieren</ion-card-title>
      <ion-card-subtitle>...</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>

    <Event></Event>

    </ion-card-content>
  </ion-card>

</template>

<script> 

import { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
import { defineComponent } from 'vue';
import Event from '@/components/Event.vue';

export default defineComponent({
  components: { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, Event },
}); 
</script>

<style scoped>
.w {
  width:100%;
}
</style>