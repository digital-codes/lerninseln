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
    
     <ion-loading
    :is-open="loading"
    cssClass="loader-class"
    message="Bitte warten..."
    >
    </ion-loading>

    <IntroText></IntroText>
    <!--    
    <LoginForm></LoginForm>
    -->

    <IntroSlides v-if="!loading" ></IntroSlides>

    <Providers></Providers>


    </ion-content>
  </ion-page>
</template>

<script lang="js">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent,  } from '@ionic/vue';

import { IonLoading } from '@ionic/vue';

import { defineComponent, ref } from 'vue'; 

//import ExploreContainer from '@/components/ExploreContainer.vue';
//import LoginForm from '@/components/LoginForm.vue';
import IntroText from '@/components/IntroText.vue';
import IntroSlides from '@/components/IntroSlides.vue';
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

import { Device } from '@capacitor/device';



export default  defineComponent ({
  name: 'Intro',
  components: { //LoginForm, 
    IntroText, Providers, IonHeader, IonToolbar, IonTitle, IonContent, IonPage,IntroSlides,IonLoading
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
    console.log("QR length:", this.store.state.qrcode.length)
    const dt = new Date()
    const date = dt.toISOString().split("T")[0]
    const tm = dt.toLocaleTimeString('de-DE')
    const time = tm.split(":")[0] + ":" + tm.split(":")[1]
    console.log("date: ",date, time)

    this.df = await DataFetch.getInstance()
    this.ds = await DataStorage.getInstance()

    // device info
    try {
      const info = await Device.getInfo()  
      const platform = info.platform.toLowerCase()
      console.log("Info: ",info)
      console.log("Platform: ",platform)
      if ((platform == "android") || (platform == "ios")) {
        await this.store.commit(MUTATIONS.SET_DEVICE, {platform:platform})
        console.log("Mobile device detected: ",platform)
      }
    } catch {
      console.log("No device info")
    }
    /* test 
    const x = await this.df.getTable("ticket");
    console.log("X:",x)
     test */
    const dbMagic = await this.ds.getItem("magic")
    if ((dbMagic || 0) == 0) {
      console.log("Problem with DataStore")
      console.log("Magic not set")
    } else {
      console.log("Datastore verified")

      /* remote log
      let posting = {request:9,payload:{text:"Datastore OK"}}
      await this.df.post(posting)
      */
      // load data
      const tables = ["config","provider","event","ticket","feature","category","audience"]
      for (const ti in tables) {
        const t = tables[ti]
        console.log("Get data for table ",t);
        const result = await this.df.getTable(t);
        //console.log("Table ",t,": ",result)
        await this.ds.setItem(t, JSON.stringify(result))
      }
      // try to load id
      const idString = await this.ds.getItem("identity") || ""
      //console.log("ID from db: ",dbId)
      if (idString.length > 0) {
        const dbId = JSON.parse(idString)
        const id = {
          "email": dbId.email,
          "pwd": dbId.pwd
        }
        /* remote log
        posting = {request:9,payload:{text:"Identity: valid"}}
        await this.df.post(posting)
        */
        //console.log("Saving id: ",id)
        await this.store.commit(MUTATIONS.SET_ID,id)
        //console.log("Store id: ",this.store.state.identity)

      }
      console.log("Loading complete")
      this.loading = false

    }

  },
  setup() {
    const loading = ref(true);

    const store = useStore();
    const ionRouter = useIonRouter();
    useBackButton(-1, () => {
      if (!ionRouter.canGoBack()) {
        App.exitApp();
      }
    });
    return { store, loading};
  }
})

</script>


<style scoped>

ion-toolbar {
  /* https://ionicframework.com/docs/api/toolbar */
  /* background color from app.vue */
  --border-color:#048500;
  --border-width:3px;
  --border-style:solid none none none;
}

/* https://stackoverflow.com/questions/54956372/how-to-change-the-toolbar-color-in-ionic-4 

*/

.loader-class {

}

ion-content {
    --background: url('/assets/img/bg/backIcons_white.png');
    --background-repeat: repeat;
}

/*
.ion-page {
  max-width:960px;
  margin-left:auto;
  margin-right:auto;
}
*/

</style>
