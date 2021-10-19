<template>

  <ion-card ion-fixed>
    <ion-card-header >
      <ion-card-title class="hdr">Anbieter</ion-card-title>
      <ion-card-subtitle class="hdr">Kategorie </ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>
    <ul class="list">
      <li v-for="item in items"  :key="item.id" class="listItem">
            <h2>{{item.hdr}}</h2>
            <p>{{item.text}}</p>
      </li>
    </ul>
    </ion-card-content>

  </ion-card>


</template>


<!-- check https://forum.ionicframework.com/t/css-position-fixed/2969/4 -->

<script> 

import { defineComponent } from 'vue'; 

import { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, } from '@ionic/vue';
import { walk, warning, wifi, wine } from 'ionicons/icons';

export default defineComponent({
  // ----------------------
  components: {  IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, },
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

.event {
  text-align: left;
  white-space: normal;
}
.eventDate {
  text-align: left;
  /* with position: fixed adjust size */
  flex-basis: 150px;
}

.footer {
  height: 1rem;
  background-color: primary;

}

.hdr {
  --color: --ka-text-color;  
}


</style>

