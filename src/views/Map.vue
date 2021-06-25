<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Map
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>

      <ion-card slot="fixed" >
      <ion-card-content>

      <Leaf></Leaf>

      </ion-card-content>
      </ion-card>

    <!--ion-content :fullscreen="true"-->
    <ion-content >
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Map</ion-title>
        </ion-toolbar>
      </ion-header>

      <ion-card >
      <ion-card-content>

        <Providers></Providers>

      </ion-card-content>
      </ion-card>


    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent } from '@ionic/vue';
import Leaf from "@/components/Leaf.vue";
import Providers from '@/components/Providers.vue';

export default  {
  name: 'Map',
  components: { IonHeader, IonToolbar, IonTitle, IonContent, IonPage,Leaf, Providers }
}
</script>
