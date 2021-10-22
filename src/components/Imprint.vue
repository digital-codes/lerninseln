<template>
    <ion-card>
    <ion-card-content class="center">
        <div v-if="isMobile()">
          <ion-button  @click="imprint()">
            <ion-icon :icon="informationCircleOutline" />
          <ion-label>Impressum</ion-label>
          </ion-button>
          <ion-button  @click="gdpr()">
            <ion-icon :icon="shieldOutline" />
          <ion-label>Datenschutz</ion-label>
          </ion-button>
        </div>
        <div v-else>
          <ion-button  :href="imprintUrl" target="_blank">
            <ion-icon :icon="informationCircleOutline" alt="Impressum"/>
          <ion-label>Impressum</ion-label>
          </ion-button>
          <ion-button  :href="gdprUrl" target="_blank">
            <ion-icon :icon="shieldHalfOutline" alt="Datenschutz"/>
          <ion-label>Datenschutz</ion-label>
          </ion-button>
        </div>

    </ion-card-content>
  </ion-card>

</template>

<script lang="ts">
import {IonCard, IonCardContent, IonButton, IonIcon, IonLabel } from '@ionic/vue';
import { defineComponent } from 'vue';

import { Plugins } from '@capacitor/core';
const { Browser } = Plugins;
import { useStore } from '../services/quickStore';


import { 
  informationCircleOutline,
  shieldHalfOutline,
 } from 'ionicons/icons';


export default defineComponent ({
  name: "Imprint",
  components: { IonCard, IonCardContent, IonLabel, 
     IonButton, IonIcon
  },
  methods: {
    isMobile(){
      console.log("Platform: ",this.store.state.device.platform)
      return this.store.state.device.platform != "web"
    },
    imprint() {
      Browser.open({ 'url': this.imprintUrl }).then((r: any) => {console.log("imprint loaded:",r)})
    },
    gdpr() {
      Browser.open({ 'url': this.gdprUrl }).then((r: any) => {console.log("gdpr loaded:",r)})
    },
  },
  setup() {
    const store = useStore()
    return {
      store,
      informationCircleOutline,shieldHalfOutline,
      gdprUrl:"https://www.karlsruhe.de/impressum/datenschutz.de",
      imprintUrl: "https://www.karlsruhe.de/impressum.de",
    }
  },

});
</script>

<style scoped>

.extLink {
  text-align: left;
  /* with position: fixed adjust size */
  display: block;
  text-decoration-line: underline;
  text-decoration-color: #048500; 
  color: #048500;
}

.extLink:hover {
    cursor:pointer;
}

.hdr {
  color: unset;  
}

.center {
  text-align: center;

}
</style>
