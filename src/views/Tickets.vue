<template>
  <ion-page>
    <!--
    <ion-header>
      <ion-toolbar>
        <ion-title>Tickets
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>
    -->
    <ion-content >
      <ion-card>
      <ion-card-content>

      <div v-if="hasEvent">
        <Event 
          :date=getEvent.date 
          :time=getEvent.time 
          :title=getEvent.title 
          :text=getEvent.provider 
          :id=getEvent.id
          :icon=getEvent.category_id
          >
        </Event>

        <OrderForm 
          @purchaseComplete="purchaseCompleted($event)"
          info="Testbetrieb! Gib irgendeine Email und dann einen beliebigen Code ein"
        ></OrderForm>
        <!--
        <ion-item>
        <Ion-button class="center-button" @click="presentActionSheet()">
          Buchen
        </Ion-button>
        </ion-item>
        -->
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
import { IonPage, IonButton, IonHeader, 
  IonToolbar, IonTitle, 
  IonContent,IonCard, IonCardContent,
  modalController } from '@ionic/vue';

import QrModal from '@/components/QrModal.vue'

import Event from '@/components/Event.vue';
import { defineComponent } from 'vue'; 

import { rocket, trash,  } from 'ionicons/icons';

// load all data from server and write to database
import DataStorage from "../services/dstore";

// https://next.vuex.vuejs.org/guide/composition-api.html#accessing-state-and-getters

import { useStore, Selection, Qrcode, Purchase, MUTATIONS } from '../services/quickStore';

// test
import OrderForm from '@/components/OrderForm.vue';

// storage
import { Capacitor, Plugins, Encoding, FilesystemDirectory } from '@capacitor/core';
const { Filesystem } = Plugins;

/* passing data from child to parent 
https://forum.vuejs.org/t/passing-data-back-to-parent/1201
https://dev.to/freakflames29/how-to-pass-data-from-child-to-parent-in-vue-js-2d9m
https://v3.vuejs.org/guide/migration/emits-option.html#_3-x-behavior
*/

export default  defineComponent ({
  name: 'Tickets',
  data () {
    return {
      //evnt: {},
      items : [],
      ds: "",
    }
  },
  components: { Event, IonContent, IonPage,IonCard, IonCardContent, OrderForm  },
  methods: {
    purchase() {
      console.log("Buy ticket: ")
    },
    async purchaseCompleted(result) {
        console.log("Completed: ",result,"status: ",
        ", ticket: ",this.store.state.purchase.ticket)
        if (result.status) {
          // prepare new qr
          const event = this.items[this.store.state.selection.eventId - 1]
          console.log("Event: ",event)
          const qr = {}
          qr.qrsrc = result.data.qr
          qr.title = event.title
          qr.date = event.date
          qr.time = event.time
          qr.provider = event.provider
          qr.count = 1
          // ----------------
          // local store
          /*
          const time = new Date().getTime()
          const fileName = "QR" + time + ".jpeg"
          console.log("Writing to ",fileName)
          if (Filesystem) {
            console.log("Now Writing")
            await Filesystem.writeFile({
                  data: qr.qrsrc,
                  path: fileName,
                  directory: FilesystemDirectory.Data,
                  encoding: Encoding.UTF8,
                });
          } else {
            console.log("Can't write")
          }
          */
          // ----------------
          await this.openQr(qr)
          this.store.commit(MUTATIONS.RESET_PURCHASE)
          this.store.commit(MUTATIONS.RESET_EVENT)
          console.log("Event now:",(this.store.state.selection.eventId != 0) ? "set" : "reset")
          console.log('Purchase completed and reset');
          await this.store.commit(MUTATIONS.ADD_QR,qr)
          console.log("total QRs: ",this.store.state.qrcode.length)
          //this.presentActionSheet()
        }
    },
    async openQr(data) {
      // see also https://stackoverflow.com/questions/65740559/cant-close-the-modal-in-ionic-vue-5-5-2
      const info = "Buchung erfolgreich. Code ist in der Code-Liste"
      const modal = await modalController
        .create({
          component: QrModal,
          cssClass: 'my-custom-class',
          componentProps: {
          title: data.title,
          qrsrc:data.qrsrc,
          date: data.date,
          time: data.time,
          count: data.count,
          provider: data.provider,
          info: info
          },
        })
      await modal.present()
      await modal.onDidDismiss();
      console.log('Modal dismissed');
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
    this.ds = await DataStorage.getInstance()
    const providerString = await this.ds.getItem("provider") || "[]"
    const providers = JSON.parse(providerString)
    const itemString = await this.ds.getItem("event") || "[]"
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

.center-button {
  display: block;
  margin: auto;
}

.center-button>button {
  padding: 1em;
}

</style>
