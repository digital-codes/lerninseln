<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Codes
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content >
      <ion-card>
      <ion-card-content>

        <BookShow></BookShow>

      </ion-card-content>
      </ion-card>

    </ion-content>

  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent,IonCard, IonCardContent  } from '@ionic/vue';
import BookShow from '@/components/BookShow.vue';

export default  {
  name: 'Codes',
  components: { BookShow, IonHeader, IonToolbar, IonTitle, IonContent, IonPage,IonCard, IonCardContent  }
}
</script>
