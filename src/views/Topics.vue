<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Topics</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Topics</ion-title>
        </ion-toolbar>
      </ion-header>
      
      <Providers></Providers>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/vue';
import Providers from '@/components/Providers.vue';

export default  {
  name: 'Topics',
  components: { Providers,IonHeader, IonToolbar, IonTitle, IonContent, IonPage }
}
</script>
