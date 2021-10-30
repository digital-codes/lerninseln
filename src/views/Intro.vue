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
    <div ref="tab1" class="swiping">
    
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

    <!--
    <IntroSlides v-if="!loading" ></IntroSlides>
    -->

    <Providers></Providers>

    <Imprint></Imprint>

    </div>
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
import Imprint from '@/components/Imprint.vue';

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

import { createGesture } from '@ionic/vue';
import router from "../router";


export default  defineComponent ({
  name: 'Intro',
  components: { //LoginForm, 
    IntroText, Providers, IonHeader, IonToolbar, IonTitle, 
    IonContent, IonPage, Imprint, //IntroSlides,
    IonLoading
  },
  data: function() {
    return {
      email: "",
      pwd: "",
      ds: "",
      df: "",
    } 
  },
  methods : {
    onSwipe(detail) {
      const type = detail.type;
      const cx = detail.currentX;
      const dx = detail.deltaX;
      const vx = detail.velocityX;
      //console.log(type,cx,dx,vx)
      //if ((type == "pan") && (dx > 100) && (vx > 1)) router.push("/tabs/tab3")
      if ((type == "pan") && (dx < -100) && (vx < -1)) router.push("/map")
    },
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
        if (t == "event") {
          // filter only future events
          const dt = new Date()
          const date = dt.toISOString().split("T")[0]
          //console.log("date: ",date)
          const events = result.filter(e => e.date >= date)
          await this.ds.setItem(t, JSON.stringify(events))
        } else {
          await this.ds.setItem(t, JSON.stringify(result))
        }
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

        // loading codes from database if id exists
        console.log("Loading qrcodes")
        await this.store.commit(MUTATIONS.RESET_QR)
        const qrString = await this.ds.getItem("code") || "[]"
        if (qrString.length > 0) {
          const qrCodes = JSON.parse(qrString)
          for (const qr of qrCodes) {
            // add qr does sorting
            await this.store.commit(MUTATIONS.ADD_QR,qr)
            console.log("Added code for ",qr.title)
          }
        }

      }

      console.log("Loading complete")
      this.loading = false


    }
  },
  async mounted(){
    const gest = this.$refs.tab1 //ref();
    // const r = router.currentRoute.value.path // value is important!
    //console.log("Current: ",r,"ref2:",gest)
    //setTimeout(function(){console.log(r.path)},2000)
    const gesture = createGesture({
      onMove: (detail) => { this.onSwipe(detail);},
      gestureName: 'swipe',
      el: gest,
    })
    gesture.enable();
  },
  setup() {
    const loading = ref(true);

    const store = useStore();
    /*
    const ionRouter = useIonRouter();
    useBackButton(-1, () => {
      if (!ionRouter.canGoBack()) {
        App.exitApp();
      }
    });
    */
    const ionRouter = useIonRouter();
    useBackButton(-1, () => {
      if ((!ionRouter.canGoBack()) || (router.currentRoute.value.path == "/intro")) {
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

/* can overwrite here ... **
ion-content {
    --background: url('/assets/img/bg/backIcons_white.png');
    --background-repeat: repeat;
}
*/

/*
.ion-page {
  max-width:960px;
  margin-left:auto;
  margin-right:auto;
}
*/
.swiping {
  min-height:90%;
}

</style>
