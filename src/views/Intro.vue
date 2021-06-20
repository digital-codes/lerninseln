<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Intro</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Intro</ion-title>
        </ion-toolbar>
      </ion-header>
    
    <ion-card>
    <ion-card-header>
      <ion-card-title>Card Title</ion-card-title>
      <ion-card-subtitle>Card Subtitle</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>
      Keep close to Nature's heart... and break clear away, once in awhile,
      and climb a mountain or spend a week in the woods. Wash your spirit clean.
    </ion-card-content>
  </ion-card>

    <ion-card>
    <ion-card-header>
      <ion-card-title>Login Form</ion-card-title>
      <ion-card-subtitle>... fake, so ...</ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>
      <!-- login -->
      <ion-input type="email" placeholder="Email" :email="email" ></ion-input>
      <ion-input type="password" placeholder="Password" :pwd="pwd" ></ion-input>
    </ion-card-content>
  </ion-card>


    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonInput, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
//import ExploreContainer from '@/components/ExploreContainer.vue';

export default  {
  name: 'Intro',
  components: { IonHeader, IonToolbar, IonTitle, IonContent, IonPage, IonInput, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle },
  data: function() {
    return {
      email: "",
      pwd: "",
    } 
  },
}
</script>
