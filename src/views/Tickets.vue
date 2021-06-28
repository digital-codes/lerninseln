<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Tickets
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content >
      <ion-card>

      <div>
      <ion-button @click="addEvent">Add</ion-button>
      <ion-button @click="clrEvent">Clr</ion-button>
      </div>

      <div v-if="getEvent" >
        Event ID: {{ store.state.selection.eventId }}
      </div>


      <ion-card-content>

        <BookNew></BookNew>

      </ion-card-content>
      </ion-card>

    </ion-content>

  </ion-page>
</template>

<script lang="js">
import { IonPage, IonButton, IonHeader, IonToolbar, IonTitle, IonContent,IonCard, IonCardContent  } from '@ionic/vue';
import BookNew from '@/components/BookNew.vue';

// https://next.vuex.vuejs.org/guide/composition-api.html#accessing-state-and-getters

import { useStore, Todo, Selection, MUTATIONS } from '../store';

export default  {
  name: 'Tickets',
  components: { BookNew, IonButton, IonHeader, IonToolbar, IonTitle, IonContent, IonPage,IonCard, IonCardContent  },
  methods: {
    addEvent() {
      this.store.commit(MUTATIONS.SET_EVENT, 2);
    },
    clrEvent() {
      this.store.commit(MUTATIONS.RESET_EVENT);
    },
  },
  computed: {
    //getEvent(): number {
    getEvent() {
      const ev = this.store //.selection.eventId;
      console.log("Ev:",ev)
      return (ev.state.selection.eventId != 0)
    }
  },
    // store
  setup() {
    const store = useStore();
    return { store };
  },

}
</script>
