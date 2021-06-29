<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title :dbMagic="dbMagic" >Intro  {{ dbMagic }}
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>

   
    <ion-content :fullscreen="true" >
    <button @click="dstest()">test</button>
    
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
//import ExploreContainer from '@/components/ExploreContainer.vue';
//import LoginForm from '@/components/LoginForm.vue';
import IntroText from '@/components/IntroText.vue';
import Providers from '@/components/Providers.vue';

// load all data from server and write to database
import {initDataStore, setDataStore, getDataStore } from "../datastore";

import DataStorage from "../services/dstore";


// app exit
// https://ionicframework.com/docs/developing/hardware-back-button
import { useBackButton, useIonRouter } from '@ionic/vue';
import { Plugins } from '@capacitor/core';
const { App } = Plugins;


export default  {
  name: 'Intro',
  components: { //LoginForm, 
    IntroText, Providers, IonHeader, IonToolbar, IonTitle, IonContent, IonPage,
  },
  data: function() {
    return {
      email: "",
      pwd: "",
      dbMagic:0,
      ds: "",
    } 
  },
  methods:{
    async dstest() {
    /* test */
    console.log("DS:",this.ds)
    this.ds.setItem("test","123");
    this.ds.setItem("magic","deadbeef")
    const a = await this.ds.getItem("magic")
    console.log("Magic:",a) 
    const b = await this.ds.getItem("test")
    console.log("Test:",b) 
    /* */
    },
  },
  async beforeMount() {
    this.ds = await DataStorage.getInstance()
    await initDataStore()
    const dbMagic = await getDataStore("magic")
    if ((dbMagic || 0) == 0) {
      console.log("Problem with DataStore")
      console.log("Magic set")
    } else {
      console.log("Datastore verified")
      this.dbMagic = dbMagic

      // load data
      // cors: https://web.dev/cross-origin-resource-sharing/
      const axios = await import ('axios');
      const baseUrl = "https://lerninseln.ok-lab-karlsruhe.de/simpleSrv.php?table=";
      const config = { headers: {'Access-Control-Allow-Origin': '*'}}

      const tables = ["provider","event","ticket","category","audience"]
      for (const ti in tables) {
        const t = tables[ti]
        const url = baseUrl + t
        console.log("Axios from ",url);
        let result = []
        await axios.get(url,config)
        .then(response => {
          //console.log("Response:",response.data);
          result = response.data
        })
        .catch(error => {
            console.log("Axios error:", error);
        });
        //console.log("Table ",t,": ",result)
        await setDataStore(t, JSON.stringify(result))

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
}
</script>
