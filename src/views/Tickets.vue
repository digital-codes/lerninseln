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

      <ion-card-content>

      <div v-if="hasEvent">
        Event ID: {{ store.state.selection.eventId }}
        <Event v-model="getEvent" 
          :date=getEvent.date 
          :time=getEvent.time 
          :title=getEvent.title 
          :text=getEvent.provider_id 
          :id=getEvent.id 
          >
        </Event>
      </div>
      <div v-else>
        No event selected
      </div>

      </ion-card-content>

      </ion-card>


    </ion-content>

  </ion-page>
</template>

<script lang="js">
import { IonPage, IonButton, IonHeader, IonToolbar, IonTitle, IonContent,IonCard, IonCardContent  } from '@ionic/vue';
import Event from '@/components/Event.vue';
import { defineComponent } from 'vue'; 
// database
import {initDataStore, setDataStore, getDataStore } from "../datastore";

// https://next.vuex.vuejs.org/guide/composition-api.html#accessing-state-and-getters

import { useStore, Selection, MUTATIONS } from '../store';

export default  defineComponent ({
  name: 'Tickets',
  data () {
    return {
      evnt: {},
      items : [],
    }
  },
  components: { Event, IonButton, IonHeader, IonToolbar, IonTitle, IonContent, IonPage,IonCard, IonCardContent  },
  methods: {
    addEvent() {
      this.store.commit(MUTATIONS.SET_EVENT, 2);
    },
    clrEvent() {
      this.store.commit(MUTATIONS.RESET_EVENT);
    },
  },
  computed: {
    hasEvent() {
      return (this.store.state.selection.eventId != 0)
    },
    getEvent() {
      if (this.store.state.selection.eventId != 0) {
        const evnt = this.items[this.store.state.selection.eventId - 1] 
        //console.log("event item: ",evnt)
        return evnt
      } else 
      return {}
    }
  },
  async beforeMount() {
    await initDataStore()
    const items = await getDataStore("event") || "[]"
    this.items = JSON.parse(items)
  },
    // store
  setup() {
    const store = useStore();
    return { store };
  },
})

</script>
