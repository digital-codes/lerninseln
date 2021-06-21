<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Intro
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Intro</ion-title>
        </ion-toolbar>
      </ion-header>
    
    <IntroText></IntroText>
    
    <LoginForm></LoginForm>

    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent,  } from '@ionic/vue';
//import ExploreContainer from '@/components/ExploreContainer.vue';
import LoginForm from '@/components/LoginForm.vue';
import IntroText from '@/components/IntroText.vue';

export default  {
  name: 'Intro',
  components: { LoginForm, IntroText, IonHeader, IonToolbar, IonTitle, IonContent, IonPage },
  data: function() {
    return {
      email: "",
      pwd: "",
    } 
  },
}
</script>
