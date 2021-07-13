<template>
    <ion-card>
    <ion-card-header>
      <ion-card-subtitle>Eine Initiative der Stadt Karlsruhe</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>
        Die Idee der „Lerninseln“ ist es, schulische sowie außerschulische Lernorte aufzuzeigen, die
        die unterschiedlichste Angebote machen und das Lernen in der „realen Welt“ ermöglichen.
        Diese Orte werden in einer interaktiven Karte sichtbar und zugänglich gemacht. 
        <div>
          <a href="https://lerninseln.ok-lab-karlsruhe.de" target=_blank>Lerninseln Webseite</a>
        </div>
        <div>
          <a href="mailto:lerninseln@ok-lab-karlsruhe.de">EMail</a>
        </div>
    </ion-card-content>
  </ion-card>


</template>

<script lang="ts">
import {IonCard, IonCardContent, IonCardHeader, IonCardSubtitle,  } from '@ionic/vue';
import { defineComponent } from 'vue';


export default defineComponent ({
  name: "IntroText",
  components: { IonCard, IonCardContent, IonCardHeader, IonCardSubtitle,  },

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
</style>
