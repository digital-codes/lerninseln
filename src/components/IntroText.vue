<template>
    <ion-card>
    <ion-card-header>
      <ion-card-title>Lerninseln</ion-card-title>
      <ion-card-subtitle>Eine Initiative der Stadt Karlsruhe</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>
        Die Idee der „Lerninseln“ ist es, diese Orte in einer interaktiven Karte von Karlsruhe zu
        verorten und sichtbar zu machen. Schulische, sowie außerschulische Lernorte aufzuzeigen, die
        die unterschiedlichste Angebote machen und das Lernen in der „realen Welt“ ermöglichen.
        Vor allem im MINT-Bereich gibt es bereits zahlreiche Initiativen und Lernorte in Karlsruhe, die
        durch die interaktive Karte deutlich sichtbarer und besser gefunden werden können.
    </ion-card-content>
  </ion-card>


</template>

<script lang="ts">
import {IonCard, IonCardContent, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
import { defineComponent } from 'vue';


export default defineComponent ({
  name: "IntroText",
  components: { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle },

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
