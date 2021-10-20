<template>
    <ion-card>
    <ion-card-header>
      <ion-card-title class="hdr">Eine Initiative der Stadt Karlsruhe</ion-card-title>
    </ion-card-header>

    <ion-card-content>
        Die Idee der „Lerninseln“ ist es, schulische sowie außerschulische Lernorte aufzuzeigen, die
        die unterschiedlichsten Angebote machen und das Lernen in der „realen Welt“ ermöglichen.
        Diese Orte werden in einer interaktiven Karte sichtbar und zugänglich gemacht. 

        <ion-img src="/assets/img/front/1.jpg"></ion-img>


        <div v-if="isMobile()">
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
        </div>
        <div v-else>
          <div>
          <ion-button  href="mailto:lerninseln@ok-lab-karlsruhe.de">
            <ion-icon :icon="mailOutline" />
          <ion-label>Schreib uns</ion-label>
          </ion-button>
          </div>
        </div>

    </ion-card-content>
  </ion-card>


</template>

<script lang="ts">
import {IonCard, IonCardContent, IonCardHeader, IonCardTitle, IonButton, IonIcon } from '@ionic/vue';
import { defineComponent } from 'vue';

import { useStore } from '../services/quickStore';

// https://capacitorjs.com/docs/apis/share
import { Share } from '@capacitor/share';

import { 
  shareSocialOutline,
  mailOutline,
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
  },
  setup() {
    const store = useStore()
    return {
      store,
      mailOutline,shareSocialOutline,
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
