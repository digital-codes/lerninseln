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
    
    <LoginForm></LoginForm>

    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonInput, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
//import ExploreContainer from '@/components/ExploreContainer.vue';
import LoginForm from '@/components/LoginForm.vue';

export default  {
  name: 'Intro',
  components: { LoginForm, IonHeader, IonToolbar, IonTitle, IonContent, IonPage },
  data: function() {
    return {
      email: "",
      pwd: "",
    } 
  },
}
</script>
