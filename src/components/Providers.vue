<template>

<ul class="list">
  <li v-for="item in items"  :key="item.id" class="listItem">
        <h2>{{item.hdr}}</h2>
        <p>{{item.text}}</p>
  </li>
</ul>

<ion-card>
    <ion-card-header>
      <ion-card-subtitle>Card Subtitle</ion-card-subtitle>
      <ion-card-title>Card Title</ion-card-title>
    </ion-card-header>

    <ion-card-content>
      Keep close to Nature's heart... and break clear away, once in awhile,
      and climb a mountain or spend a week in the woods. Wash your spirit clean.
    </ion-card-content>

    <ion-item href="#" class="ion-activated">
      <ion-icon :icon="wifi" slot="start"></ion-icon>
      <ion-label>Card Link Item 1 activated</ion-label>
    </ion-item>

    <ion-item href="#">
      <ion-icon :icon="wine" slot="start"></ion-icon>
      <ion-label>Card Link Item 2</ion-label>
    </ion-item>

    <ion-item class="ion-activated">
      <ion-icon :icon="warning" slot="start"></ion-icon>
      <ion-label>Card Button Item 1 activated</ion-label>
    </ion-item>

    <ion-item>
      <ion-icon :icon="walk" slot="end"></ion-icon>
      <ion-label>Card Button Item 2</ion-label>
      <ion-content>dmqdmmdf</ion-content>
    </ion-item>
    <ion-item>
      <ion-icon :icon="walk" slot="start"></ion-icon>
      <ion-button slot="end">dmqdmmdf</ion-button>
      <ion-label>Card Button Item 2</ion-label>
      <ion-label>Card Button Item 2</ion-label>
      <ion-label>Card Button Item 2</ion-label>
    </ion-item>
    <ion-item>
      <ion-icon :icon="wifi" slot="start"></ion-icon>
      <ion-label>Card Button Item 2</ion-label>
      <ion-label>Card Button Item 2</ion-label>
      <ion-label>Card Button Item 2</ion-label>
      <ion-button slot="start">dmqdmmdf</ion-button>
    </ion-item>
    <ion-item>
      <ion-icon :icon="walk" slot="start"></ion-icon>
      <ion-label>Card Button Item 2</ion-label>
    </ion-item>
  </ion-card>

<ion-card>
    <ion-card-header>
      <ion-card-subtitle>Card Subtitle</ion-card-subtitle>
      <ion-card-title>Card Title</ion-card-title>
    </ion-card-header>

<ion-item>
    <ion-label>DD.MM.YYYY H:mm</ion-label>
    <ion-datetime display-format="DD.MM.YYYY H:mm" min="1997" max="2030" value="2021-06-23T11:06Z"></ion-datetime>
      <ion-icon :icon="walk" slot="start"></ion-icon>
      <ion-label>Card Button Item 2</ion-label>
      bla bla bla
  </ion-item>
<ion-item>
      <ion-icon :icon="walk" slot="end"></ion-icon>
    <ion-label>DD.MM.YYYY H:mm</ion-label>
    <ion-datetime display-format="DD.MM.YYYY H:mm" min="1997" max="2030" value="2021-06-23T11:06Z"></ion-datetime>
  </ion-item>
<ion-item>
    <ion-label>DD.MM.YYYY H:mm</ion-label>
    <ion-datetime display-format="DD.MM.YYYY H:mm" min="1997" max="2030" value="2021-06-23T11:06Z"></ion-datetime>
  </ion-item>

  </ion-card>

</template>

<script> 

import { defineComponent } from 'vue'; 

import {IonDatetime, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, IonIcon, IonItem, IonLabel } from '@ionic/vue';
import { walk, warning, wifi, wine } from 'ionicons/icons';

export default defineComponent({
  // ----------------------
  components: { IonDatetime, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, IonIcon, IonItem, IonLabel},
  setup() {
    return { warning, walk, wine, wifi };
  },
  // ---------------------
  data: function() {
    return {
      items: [{
      'hdr': 'Orte der digitalen Teilhabe',
      'text': 'Hier finden die Menschen/SchülerInnen \
            freies WLAN und ggfs. bei Bedarf und Verfügbarkeit ExpertInnen, \
            die einem bei technischen Fragen weiterhelfen können.',
      "id":1,
    }, 
    {
      'hdr': 'MINT-Spaces',
      'text': 'Es gibt in Karlsruhe bereits zahlreiche tolle Initiativen\
            und Aktivitäten, die alle im Rahmen von AGs in Schulen, \
            aber auch außerhalb der Schulen \
            stattfinden, damit Kinder- und Jugendliche unsere Welt erfahren können.',
      "id":2,
    }, 
    {
      'hdr': 'Digitale Dritte Orte',
      'text': 'Orte an denen offen und \
            partizipativ (mit digitalen \
            Medien) gelernt werden kann.',
      "id":3,
    },
    {
      'hdr': 'Beteiligungsorte',
      'text': 'Orte an denen \
            Einzelaktionen (Hackathons, Kunstaktionen, Fischertechnik-Challenges usw.) \
            stattfinden',
      "id":3,
    },
    {
      'hdr': 'Virtuelle Orte',
      'text': ' Orte an denen virtuelle Treffen möglich sind.',
      "id":3,
    },
    ],
    }
  },
  methods:{
    zoom(e) {
      console.log(e)
    }
  }
}); 
</script>

<style scoped>
.list {
  list-style: none;
  padding: 0 2% 0 2%;
}

.listItem {
  padding-top: .3rem;
  padding-bottom: 1rem;
  border-bottom: solid 1px;
  border-bottom-color: #444;
}

h2 {
  padding-bottom: .2rem;
}

</style>

