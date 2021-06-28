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
      <!--
      <div>
      <ion-button @click="addEvent">Add</ion-button>
      <ion-button @click="clrEvent">Clr</ion-button>
      </div>
      -->
      <ion-card-content>

      <div v-if="hasEvent">
        <Event 
          :date=getEvent.date 
          :time=getEvent.time 
          :title=getEvent.title 
          :text=getEvent.provider 
          :id=getEvent.id
          >
        </Event>

        <Ion-button class="center" @click="presentActionSheet()">
          Buchen
        </Ion-button>

      </div>
      <div v-else> 
        <h2>Nichts ausgewählt!</h2>
        Bitte wähle zuerst eine Veranstaltung aus.
      </div>

      </ion-card-content>

      </ion-card>


    </ion-content>

  </ion-page>
</template>

<script lang="js">
import { IonPage, IonButton, IonHeader, IonToolbar, IonTitle, 
  IonContent,IonCard, IonCardContent,
  actionSheetController,
  } from '@ionic/vue';
import Event from '@/components/Event.vue';
import { defineComponent } from 'vue'; 

import { rocket, trash,  } from 'ionicons/icons';

// database
import {initDataStore, setDataStore, getDataStore } from "../datastore";

// https://next.vuex.vuejs.org/guide/composition-api.html#accessing-state-and-getters

import { useStore, Selection, MUTATIONS } from '../store';

export default  defineComponent ({
  name: 'Tickets',
  data () {
    return {
      //evnt: {},
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
    purchase() {
      console.log("Buy ticket: ")
    },

    async presentActionSheet() {
      const actionSheet = await actionSheetController
        .create({
          header: 'Buchung',
          buttons: [
            {
              text: 'Bestätigen',
              icon: rocket,
              role: 'OK',
              handler: () => {
                this.purchase()
              }
            },
            {
              text: 'Abbrechen',
              icon: trash,
              role: 'cancel',
              handler: () => {
                console.log('Cancel clicked')
              },
            },
          ],
        });
      await actionSheet.present();
      const { role } = await actionSheet.onDidDismiss();
      console.log('onDidDismiss resolved with role', role);
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
      return {x:1}
    }
  },
  async beforeMount() {
    await initDataStore()
    const providerString = await getDataStore("provider") || "[]"
    const providers = JSON.parse(providerString)
    const itemString = await getDataStore("event") || "[]"
    const items = JSON.parse(itemString)
    for (let i=0; i < items.length; i++){
      const id = items[i].provider_id - 1
      const name = providers[id].name
      //console.log("i: ",i,", id: ",id, ", name: ",name)
      items[i].provider = name
    }
    this.items = items
  },
  // store
  setup() {
    const store = useStore();
    return { store };
  },
})

</script>

<style scoped>

.center {
  display: block;
}

</style>