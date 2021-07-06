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

        <div v-for="ticket in getTickets" :key=ticket.id class="tickets" >
        <Ticket
          :cost=ticket.cost 
          :costinfo=ticket.costinfo
          :limit=ticket.limit
          @costUpdate="costUpdate($event,ticket.id)"
          >
          </Ticket>
        </div>

        <p class="totalcost">Kosten: {{getCost}} €</p>

        <OrderForm 
          @purchaseComplete="purchaseCompleted($event)"
          info="Testbetrieb! Gib irgendeine Email und dann einen beliebigen 6-stelligen Code ein"
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
  IonList, IonItem, IonLabel,
  modalController } from '@ionic/vue';

import QrModal from '@/components/QrModal.vue'

import Event from '@/components/Event.vue';
import Ticket from '@/components/Ticket.vue';
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
      events: [],
      tickets : [],
      ds: "",
    }
  },
  components: { Event, IonContent, IonPage,IonCard, IonCardContent, Ticket, OrderForm },
  methods: {
    async costUpdate(e,t) {
      console.log("Costupdate",e,t)
      const tidx = this.tickets.findIndex(tick => tick.id == t)
      console.log("Index ",tidx)
      this.tickets[tidx].count = e
    },
    purchase() {
      console.log("Buy ticket: ")
    },
    async purchaseCompleted(result) {
        console.log("Completed: ",result,"status: ",
        ", ticket: ",this.store.state.purchase.ticket)
        if (result.status) {
          // prepare new qr
          //const event = this.tickets[this.store.state.selection.eventId - 1]
          const event = this.events.find(e => e.id == this.store.state.selection.eventId)
          console.log("Event: ",event)
          const qr = {}
          qr.qrsrc = result.data.qr
          qr.title = result.data.event.name
          qr.date = result.data.event.date
          qr.time = result.data.event.time
          qr.provider = result.data.event.provider
          qr.count = result.data.event.count
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
      const valid = (this.store.state.selection.eventId != 0) && (this.events.length > 0)
      return valid
    },
    getEvent() {
      if ((this.store.state.selection.eventId != 0) && (this.events.length > 0)) {
        const evnt = this.events.find(e => e.id == this.store.state.selection.eventId) 
        //console.log(this.store.state.selection.eventId, this.tickets)
        const ticket = this.tickets.filter(t => t.event_id == this.store.state.selection.eventId)
        console.log("Ticket id: ",ticket)
        return evnt
      } else 
      return {x:1}
    },
    getTickets() {
      if ((this.store.state.selection.eventId != 0) && (this.events.length > 0)) {
        console.log("Event: ", this.store.state.selection.eventId, "Tickets: ",this.tickets)
        const tickets = this.tickets.filter(t => t.event_id == this.store.state.selection.eventId)
        for (let i=0;i<tickets.length;i++) tickets[i].count = 0
        console.log("Ticket id: ",tickets)
        return tickets
      } else 
        console.log("Error: no tickets")
      return {x:1}
    },
    getCost() {
      let cost = 0
      const tickets = this.tickets.filter(t => t.event_id == this.store.state.selection.eventId)
      tickets.forEach(t => {
        console.log(t)
        cost += parseInt(t.count)*(parseInt(t.cost))
        })
      return cost 
    },
  },
  async beforeMount() {
    this.ds = await DataStorage.getInstance()
    const providerString = await this.ds.getItem("provider") || "[]"
    const providers = JSON.parse(providerString)
    const ticketString = await this.ds.getItem("ticket") || "[]"
    const tickets = JSON.parse(ticketString)
    const eventString = await this.ds.getItem("event") || "[]"
    const events = JSON.parse(eventString)
    for (let i=0; i < events.length; i++){
      const id = events[i].provider_id - 1
      const name = providers[id].name
      //console.log("i: ",i,", id: ",id, ", name: ",name)
      events[i].provider = name
    }
    this.events = events
    //this.tickets = items
    this.tickets = tickets

    console.log("FIXME: runs on events, not tickets so far")
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
