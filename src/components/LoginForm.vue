<template>
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
      <ion-input 
        required=true 
        type="email" 
        placeholder="Email" 
        v-model="email" 
        ></ion-input>
      <ion-input 
        type="password" 
        placeholder="Password" 
        v-model="pwd" 
        ></ion-input>
        <ion-button @click="submit">Submit</ion-button>
    </ion-card-content>
  </ion-card>

</template>

<script lang="ts">
import { IonInput, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
//import ExploreContainer from '@/components/ExploreContainer.vue';

export default  {
  name: 'LoginForm',
  components: { IonInput, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle },
  data: function() {
    return {
      email: "",
      pwd: "",
    } 
  },
  methods: {
      submit() {
        const credentials = {"email":this.email,"pwd":this.pwd}
        console.log("WARNING!!!", credentials)
        this.email = ""
        this.pwd = ""
      },
  }
}
</script>

<style scoped>
#container {
  text-align: center;

}

#container strong {
  font-size: 20px;
  line-height: 26px;
}

#container p {
  font-size: 16px;
  line-height: 22px;
  color: #8c8c8c;
  margin: 0;
}

#container a {
  text-decoration: none;
}
</style>
