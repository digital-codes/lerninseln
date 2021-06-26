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

      <ion-card >
      <ion-card-content>

        <Events></Events>

      </ion-card-content>
      </ion-card>


    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonCard, IonCardContent } from '@ionic/vue';
import Leaf from "@/components/Leaf.vue";
import Events from '@/components/Events.vue';

export default  {
  name: 'Map',
  components: { IonHeader, IonToolbar, IonTitle, IonContent, IonCard, IonCardContent, IonPage, Leaf ,Events }
}
</script>
