<template>
  <ion-page>
    <!--
    <ion-header>
      <ion-toolbar>
        <ion-title>Codes
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>
      -->

    <ion-content >
      <ion-card>
      <!--
      <ion-card-header>
        <ion-card-title>Deine QR Codes</ion-card-title>
      </ion-card-header>
      -->
        <BookShow></BookShow>

      </ion-card>

    </ion-content>

  </ion-page>
</template>

<script lang="ts">
import { IonPage,  IonTitle, IonContent,IonCard } from '@ionic/vue';
import BookShow from '@/components/BookShow.vue';

export default  {
  name: 'Codes',
  components: { BookShow, IonPage,IonCard,   }
}
</script>
