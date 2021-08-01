<template>
    <ion-card>
    <ion-card-header>
      <ion-card-subtitle>Eine Initiative der Stadt Karlsruhe</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>
        Die Idee der „Lerninseln“ ist es, schulische sowie außerschulische Lernorte aufzuzeigen, die
        die unterschiedlichsten Angebote machen und das Lernen in der „realen Welt“ ermöglichen.
        Diese Orte werden in einer interaktiven Karte sichtbar und zugänglich gemacht. 
        <!--
        <div>
          <a href="https://lerninseln.ok-lab-karlsruhe.de" target=_blank>Lerninseln Webseite</a>
        </div>
        -->
        <div>
          <a href="mailto:lerninseln@ok-lab-karlsruhe.de">EMail</a>
        </div>

        <div v-if="isMobile()">
        <p class="extLink" @click="imprint">Impressum
        </p>
        <p class="extLink" @click="gdpr">Datenschutz
        </p>
        </div>
        <div v-else>
        <p>
        <a :href="imprintUrl" target="_blank">Impressum</a>
        </p>
        <p>
        <a :href="gdprUrl" target="_blank">Datenschutz</a>
        </p>
        </div>

    </ion-card-content>
  </ion-card>


</template>

<script lang="ts">
import {IonCard, IonCardContent, IonCardHeader, IonCardSubtitle,  } from '@ionic/vue';
import { defineComponent } from 'vue';

import { Plugins } from '@capacitor/core';
const { Browser } = Plugins;
import { useStore, Device } from '../services/quickStore';


export default defineComponent ({
  name: "IntroText",
  components: { IonCard, IonCardContent, IonCardHeader, IonCardSubtitle,  
    
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
    //const gdpr_url = "https://www.karlsruhe.de/impressum/datenschutz.de";
    //const imprint_url = "https://www.karlsruhe.de/impressum.de"
    return {
      store,
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
  text-decoration-color: blue;
  color: blue;
}

.extLink:hover {
    cursor:pointer;
}

</style>
