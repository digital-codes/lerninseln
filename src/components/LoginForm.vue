<template>
    <ion-card>
    <ion-card-header>
      <ion-card-title>Login Form</ion-card-title>
      <ion-card-subtitle>... fake, so ...</ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>
      <!-- login -->
      <ion-input id="email" 
        required=true 
        type="email" 
        placeholder="Email" 
        v-model="email" 
        ></ion-input>
      <ion-input id="pwd" 
        type="password" 
        placeholder="Password" 
        v-model="pwd" 
        ></ion-input>
        <ion-button @click="submit">Submit</ion-button>

    </ion-card-content>
  </ion-card>

</template>

<script lang="js">
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
        // there are issues with typescript compiler
        // setting lang to ts will break this code whit ionic build
        // but not with ionic serve.
        // check transpiler ....
        /*
        const i = document.getElementById("email")
        const iv = (<HTMLInputElement>i).value;
        console.log(iv)
        */
        /*
        const e = (<HTMLInputElement>document.getElementById("email")).value;
        //const p = (<HTMLInputElement>document.getElementById("pwd")).value;
        */
        //const e = document.getElementById("email").value;
        //const p = document.getElementById("pwd").value;
        //const credentials = { "email":e, "pwd": p } ;
        // this doesn't work with npm build!
        const credentials = {"email":this.email,"pwd":this.pwd}
        console.log("WARNING!!!", credentials)
        if ((credentials.email == "") || (credentials.pwd == ""))
          return
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
