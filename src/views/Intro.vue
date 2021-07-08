<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title >Lerninseln
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>

   
    <ion-content :fullscreen="true" >
    
    <IntroText></IntroText>
    <!--    
    <LoginForm></LoginForm>
    -->

    <Providers></Providers>


    </ion-content>
  </ion-page>
</template>

<script lang="js">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent,  } from '@ionic/vue';

import { defineComponent } from 'vue'; 

//import ExploreContainer from '@/components/ExploreContainer.vue';
//import LoginForm from '@/components/LoginForm.vue';
import IntroText from '@/components/IntroText.vue';
import Providers from '@/components/Providers.vue';

// load all data from server and write to database
import DataStorage from "../services/dstore";
import DataFetch from "../services/fetch";

import { useStore, Selection, MUTATIONS } from '../services/quickStore';

// app exit
// https://ionicframework.com/docs/developing/hardware-back-button
import { useBackButton, useIonRouter } from '@ionic/vue';
import { Plugins } from '@capacitor/core';
const { App } = Plugins;



export default  defineComponent ({
  name: 'Intro',
  components: { //LoginForm, 
    IntroText, Providers, IonHeader, IonToolbar, IonTitle, IonContent, IonPage,
  },
  data: function() {
    return {
      email: "",
      pwd: "",
      ds: "",
      df: "",
    } 
  },
  async beforeMount() {
    console.log("QR length:", this.$store.state.qrcode.length)

    this.ds = await DataStorage.getInstance()
    this.df = await DataFetch.getInstance()
    /* test 
    const x = await this.df.getTable("ticket");
    console.log("X:",x)
     test */
    const dbMagic = await this.ds.getItem("magic")
    if ((dbMagic || 0) == 0) {
      console.log("Problem with DataStore")
      console.log("Magic set")
    } else {
      console.log("Datastore verified")

      // load data
      const tables = ["config","provider","event","ticket","feature","category","audience"]
      for (const ti in tables) {
        const t = tables[ti]
        console.log("Get data for table ",t);
        const result = await this.df.getTable(t);
        //console.log("Table ",t,": ",result)
        await this.ds.setItem(t, JSON.stringify(result))
      }

    }

  },
  setup() {
    const ionRouter = useIonRouter();
    useBackButton(-1, () => {
      if (!ionRouter.canGoBack()) {
        App.exitApp();
      }
    });
  }
})

</script>
