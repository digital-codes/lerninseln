<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Topics
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>

      <!-- outside ion-content allows fixed position
      above srollable area
      --> 
      <ion-card slot="fixed" >
      <ion-card-header>
        <ion-card-subtitle>Card Subtitle</ion-card-subtitle>
        <ion-card-title>Card Title</ion-card-title>
      </ion-card-header>

      <ion-card-content>
        Keep close to Nature's heart... and break clear away, once in awhile,
        and climb a mountain or spend a week in the woods. Wash your spirit clean.
      </ion-card-content>
      </ion-card>

      <!--ion-content :fullscreen="true"-->
      <ion-content>
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
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonCard,IonCardContent, IonCardSubtitle, IonCardTitle, } from '@ionic/vue';
import Providers from '@/components/Providers.vue';

export default  {
  name: 'Topics',
  components: { Providers,IonHeader, IonToolbar, IonTitle, IonContent, IonPage,IonCard,IonCardContent, IonCardSubtitle, IonCardTitle }
}
</script>
