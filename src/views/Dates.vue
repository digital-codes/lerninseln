<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Dates
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Dates</ion-title>
        </ion-toolbar>
      </ion-header>
      
      <Cal></Cal>

    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/vue';
import Cal from "@/components/Cal.vue";

export default  {
  name: 'Dates',
  components: { IonHeader, IonToolbar, IonTitle, IonContent, IonPage, Cal }
}
</script>
