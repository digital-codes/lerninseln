<template>
  <ion-page >
    <!--
    <ion-header>
      <ion-toolbar>
        <ion-title>Tickets
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>
    -->
    <!-- refresh on filter 
    <div style="height:50px;">
    <EventsRefresh/>
    <p>Refresh</p>
    </div>
    -->

    <ion-content>
      <div ref="tab3" class="swiping">


      <div v-if="hasEvent">
      <ion-card>
      <ion-card-header>
      <ion-card-title class="hdr">Du buchst jetzt ...</ion-card-title>
      </ion-card-header>
      <ion-card-content>
        <Event 
          :date=getEvent.date 
          :time=getEvent.time 
          :title=getEvent.title 
          :text=getEvent.provider 
          :id=getEvent.id
          :url=getEvent.url
          :icon=getEvent.category_id
          >
        </Event>

        <div v-for="ticket in getTickets" :key=ticket.id class="tickets" >
        <Ticket
          :cost=ticket.cost 
          :costinfo=ticket.costinfo
          :limit=ticket.limit
          :count=ticket.count
          @costUpdate="costUpdate($event,ticket.id)"
          >
          </Ticket>
        </div>

        <h2 class="totalcost">Kosten: {{getCost}} €</h2>

        <div v-if="getCost > 0">
        <Paypal 
          :invoice=payInvoice 
          :url=payUrl 
        > 
        </Paypal>
        </div>

        <OrderForm 
          @purchaseComplete="purchaseCompleted($event)"
          info=""
        ></OrderForm>
        <!--
        <ion-item>
        <Ion-button class="center-button" @click="presentActionSheet()">
          Buchen
        </Ion-button>
        </ion-item>
        -->

      </ion-card-content>

      </ion-card>

      </div>
      <div v-else> 
      <ion-card>
      <!--
      <ion-card-header>
      <ion-card-subtitle>Zur Buchung wähle bitte eine Veranstaltung aus unseren Angeboten</ion-card-subtitle>
      Im Diagram siehst Du, was im letzten Monat los war und Deinen Status. Für die Top-10 winkt eine Verlosung!
      </ion-card-header>

      <ion-card-content>

        <ScoreSheet></ScoreSheet>


      <p class="scoreHdr">
      Wie kommst Du in die <span style="font-weight:800">Top 10</span>?
      </p>
      <p class="scoreInfo">
      <ul>
        <li>
        Du besuchst Veranstaltungen, die Du über die App gebucht hast.
        </li>
        <li>
        Du bewertest die besuchten Veranstaltungen.
        </li>
        <li>
        Du bewertest die App im Store.
        </li>
        <li>
        Du teilst die App mit einer <em>Empfehlung</em>.
        </li>
      </ul>

      </p>

      </ion-card-content>
      -->
      <ion-card-header>
      <ion-card-title class="hdr">Hmmm...</ion-card-title>
      <ion-card-subtitle class="hdr">Scheinbar hast Du noch nicht das Richtige gefunden.</ion-card-subtitle>
      </ion-card-header>

      <ion-card-content>

      <!--
      <ion-img src="/assets/img/book/compass.jpg"></ion-img>
      -->
      <ion-img src="/assets/img/book/binoc.jpg"></ion-img>
      <p>Bitte wähle eine Veranstaltung aus unseren 
        <ion-button @click="toMap()" class="ka-tab-btn">
              <ion-icon :icon="albumsOutline" />
              <ion-label>Angeboten</ion-label>
        </ion-button>
      </p>
      </ion-card-content>

      </ion-card>

    </div>

      <!-- optionally display url query parameter (see router/index.js)
      <div>Query: {{query}}</div>
      -->


    </div>
    </ion-content>

  </ion-page>
</template>

<script lang="js">
import { IonPage, IonButton, IonHeader, 
  IonToolbar, IonTitle, IonCardHeader, IonCardTitle, IonCardSubtitle,
  IonContent,IonCard, IonCardContent,
  IonList, IonItem, IonLabel, 
  IonImg,
  IonIcon,
  modalController } from '@ionic/vue';

import { 
  albumsOutline,
 } from 'ionicons/icons';

import QrModal from '@/components/QrModal.vue'
//import ScoreSheet from '@/components/ScoreSheet.vue'

import Event from '@/components/Event.vue';
import Ticket from '@/components/Ticket.vue';
//import EventsRefresh from "@/components/EventsRefresh.vue"


import { defineComponent, ref } from 'vue'; 

import { rocket, trash,  } from 'ionicons/icons';

// load all data from server and write to database
import DataStorage from "../services/dstore";

// https://next.vuex.vuejs.org/guide/composition-api.html#accessing-state-and-getters

import { useStore, Selection, Qrcode, Purchase, MUTATIONS } from '../services/quickStore';

// ordering
import OrderForm from '@/components/OrderForm.vue';
import Paypal from '@/components/Paypal.vue';

// storage
import { Capacitor, Plugins, Encoding, FilesystemDirectory } from '@capacitor/core';
const { Filesystem } = Plugins;

/* passing data from child to parent 
https://forum.vuejs.org/t/passing-data-back-to-parent/1201
https://dev.to/freakflames29/how-to-pass-data-from-child-to-parent-in-vue-js-2d9m
https://v3.vuejs.org/guide/migration/emits-option.html#_3-x-behavior
*/

// router for programmed navigation
import { createGesture } from '@ionic/vue';
import router from "../router";


export default  defineComponent ({
  name: 'Tickets',
  props: {
      query: {type: String, default: ""}, // capture url query parameter from router
  }, 
  data () {
    return {
      events: [],
      tickets : [],
      ds: "",
      payUrl : "https://lerninseln.ok-lab-karlsruhe.de/pay.php",
      payInvoice : "Inv1234",
    }
  },
  components: { Event, IonContent, IonPage,IonCard, IonCardContent, IonCardTitle,
  Ticket, OrderForm,  Paypal, 
  IonButton, IonIcon,
  //ScoreSheet, 
  IonCardHeader, IonCardSubtitle, 
  //EventsRefresh,
  },
  methods: {
    async costUpdate(e,t) {
      console.log("Costupdate",e,t)
      const tidx = this.tickets.findIndex(tick => tick.id == t)
      console.log("Index ",tidx)
      let cnt = this.tickets[tidx].count
      if ((e < 0) && (cnt > 0)) cnt-- 
      if ((e > 0) && (cnt < this.tickets[tidx].limit)) cnt++ 
      for (let i=0;i<this.tickets.length;i++) this.tickets[i].count = 0 // reset all
      this.tickets[tidx].count = cnt  // update selected
      // update global ticket
      const purchase = this.store.state.purchase
      purchase.ticket = t
      purchase.count = cnt
      this.ticketCount = cnt
      this.store.commit(MUTATIONS.SET_PURCHASE,purchase)
    },
    purchase() {
      console.log("Buy ticket: ")
    },
    toMap(){
      router.push("/map")
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
          qr.ticketId = this.store.state.purchase.ticket
          qr.title = result.data.event.name
          qr.date = result.data.event.date
          qr.time = result.data.event.time
          qr.provider = result.data.event.provider
          qr.count = result.data.event.count
          qr.scored = false
          qr.eventId = event.id //maybe we don't need this: result.data.event.event_id

          console.log("New QR: ",qr)

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
          // also write to the permanent store (add does sort!)
          await this.ds.setItem("code",JSON.stringify(this.store.state.qrcode))
          //this.presentActionSheet()
          //  go to codes page
          //this.router.push('/codes')
          router.replace('/codes')
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
    onSwipe(detail) {
      const type = detail.type;
      const cx = detail.currentX;
      const dx = detail.deltaX;
      const vx = detail.velocityX;
      //console.log(type,cx,dx,vx)
      if ((type == "pan") && (dx > 100) && (vx > 1)) router.push("/map")
      if ((type == "pan") && (dx < -100) && (vx < -1)) router.push("/codes")
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
        cost += parseInt(t.count)*(parseFloat(t.cost))
        })
      return cost 
    },
  },
  async beforeMount() {
    if (this.query != "") console.log("Query: ",this.query)
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

  },
  async mounted(){
    const gest = this.$refs.tab3 //ref();
    const r = router.currentRoute.value.path // value is important!
    //console.log("Current: ",r,"ref2:",gest)
    //setTimeout(function(){console.log(r.path)},2000)
    const gesture = createGesture({
      onMove: (detail) => { this.onSwipe(detail);},
      gestureName: 'swipe',
      el: gest,
    })
    gesture.enable();
  },
  // store
  setup() {
    const store = useStore();
    /*
    const router = useRouter();
    return { router, store };
    */
    return { store, albumsOutline };
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


.hdr {
  --color: --ka-text-color;  
}

.swiping {
  min-height:90%;
}


</style>
