<template>
    <ion-card>
    <ion-card-header>
      <ion-card-title class="hdr">Eine Initiative der Stadt Karlsruhe</ion-card-title>
    </ion-card-header>

    <ion-card-content>
        Die Idee der „Lerninseln“ ist es, schulische sowie außerschulische Lernorte aufzuzeigen, die
        die unterschiedlichsten Angebote machen und das Lernen in der „realen Welt“ ermöglichen.
        Diese Orte werden in einer interaktiven Karte sichtbar und zugänglich gemacht. 
        <!--
        <div>
          <a href="https://lerninseln.ok-lab-karlsruhe.de" target=_blank>Lerninseln Webseite</a>
        </div>
        <div>
          <a href="mailto:lerninseln@ok-lab-karlsruhe.de">EMail</a>
        </div>
        -->

        <ion-img src="/assets/img/front/1.jpg"></ion-img>


        <div v-if="isMobile()">
          <!--
          <ion-button  @click="imprint()">
            <ion-icon :icon="mailOutline" />
          <ion-label>EMail</ion-label>
          -->
          <div>
          <ion-button  href="mailto:lerninseln@ok-lab-karlsruhe.de">
            <ion-icon :icon="mailOutline" />
          <ion-label>Schreib uns</ion-label>
          </ion-button>
          <ion-button  @click="shareIt()">
              <ion-icon :icon="shareSocialOutline" />
              <ion-label>Teil die App</ion-label>
          </ion-button>
          </div>
          <div>
          <ion-button  @click="imprint()">
            <ion-icon :icon="informationCircleOutline" />
          <ion-label>Impressum</ion-label>
          </ion-button>
          <ion-button  @click="gdpr()">
            <ion-icon :icon="shieldOutline" />
          <ion-label>Datenschutz</ion-label>
          </ion-button>
          </div>
          <!--
          <p class="extLink" @click="imprint">Impressum
          </p>
          <p class="extLink" @click="gdpr">Datenschutz
          </p>
          -->
        </div>
        <div v-else>
          <div>
          <ion-button  href="mailto:lerninseln@ok-lab-karlsruhe.de">
            <ion-icon :icon="mailOutline" />
          <ion-label>Schreib uns</ion-label>
          </ion-button>
          </div>
          <div>
          <ion-button  :href="imprintUrl" target="_blank">
            <ion-icon :icon="informationCircleOutline" />
          <ion-label>Impressum</ion-label>
          </ion-button>
          <ion-button  :href="gdprUrl" target="_blank">
            <ion-icon :icon="shieldOutline" />
          <ion-label>Datenschutz</ion-label>
          </ion-button>
          </div>
          <!--
          <p>
          <a :href="imprintUrl" target="_blank">Impressum</a>
          </p>
          <p>
          <a :href="gdprUrl" target="_blank">Datenschutz</a>
          </p>
          -->
        </div>

    </ion-card-content>
  </ion-card>


</template>

<script lang="ts">
import {IonCard, IonCardContent, IonCardHeader, IonCardTitle, IonButton, IonIcon } from '@ionic/vue';
import { defineComponent } from 'vue';

import { Plugins } from '@capacitor/core';
const { Browser } = Plugins;
import { useStore } from '../services/quickStore';

// https://capacitorjs.com/docs/apis/share
import { Share } from '@capacitor/share';

import { 
  informationCircleOutline,
  mailOutline,
  shieldOutline,
  shareSocialOutline
 } from 'ionicons/icons';


export default defineComponent ({
  name: "IntroText",
  components: { IonCard, IonCardContent, IonCardHeader, IonCardTitle,  
     IonButton, IonIcon
  },
  methods: {
    async shareIt() {
      await Share.share({
        title: 'Lerninsel App',
        text: 'Mach mit, gibt coole Angebote!',
        url: 'https://lerninseln.ok-lab-karlsruhe.de/',
        dialogTitle: 'Teilen',
      }) 
    },
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
    //const gdpr_url = "https://www.karlsruhe.de/impressum/datenschutz.de";
    //const imprint_url = "https://www.karlsruhe.de/impressum.de"
    return {
      store,
      informationCircleOutline,mailOutline,shieldOutline,shareSocialOutline,
      gdprUrl:"https://www.karlsruhe.de/impressum/datenschutz.de",
      imprintUrl: "https://www.karlsruhe.de/impressum.de",
    }
  },

});
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


</style>
