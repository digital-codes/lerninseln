<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Tickets</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">

    <Bookings></Bookings>

    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/vue';
import Bookings from '@/components/Bookings.vue';

export default  {
  name: 'Tickets',
  components: { Bookings, IonHeader, IonToolbar, IonTitle, IonContent, IonPage }
}
</script>
