<template>

  <!--
  <ion-item>
  <div>
  <ion-label >dweewf</ion-label>
  <ion-checkbox v-model="chk1"></ion-checkbox>
  </div>
  <div>
  <ion-label >123</ion-label>
  <ion-checkbox v-model="chk2"></ion-checkbox>
  </div>
  <div>
  <ion-label >123</ion-label>
  <ion-checkbox v-model="chk3"></ion-checkbox>
  </div>
  </ion-item>

  <ion-checkbox
        @update:modelValue="check1.isChecked = $event"
        :modelValue="check1.isChecked">
  </ion-checkbox>
  <ion-checkbox
        @update:modelValue="entry.isChecked = $event"
        :modelValue="entry.isChecked">
  </ion-checkbox>
-->
  <!--ion-button @click="toggle()">Toggle</ion-button-->


  <div v-for="item in selIitems"  :key="item.id" class="listItem">
          <Event class="eventItem" 
          :date=item.date 
          :time=item.time 
          :title=item.title 
          :text=item.provider 
          :id=item.id  
          :icon=item.category_id
          :url=item.url 
          ></Event>
          <ion-checkbox class="eventCheck" 
            @ionChange="select(item.id)"
            @update:modelValue="item.checked = $event"
            :modelValue="item.checked"
          ></ion-checkbox>
  </div>

  <ion-button 
    v-if="hasEvent" 
    @click="buy()"
    >Zur Buchung
  </ion-button>


</template>

<script> 

import { IonButton, IonCheckbox } from '@ionic/vue';

// load all data from server and write to database
import DataStorage from "../services/dstore";

import { useStore, Selection, MUTATIONS } from '../services/quickStore';

import { defineComponent } from 'vue'; 
import Event from '@/components/Event.vue';

import router from "../router";



export default defineComponent({
  name: "Events",
  components: {Event, IonButton,IonCheckbox },
  data () {
    return {
    items : [],
    providers: [],
    ds: "",
    /*
    chk1: 1, 
    chk2: 0, 
    chk3: 1,
    */ 
    }
  },
  methods:{
    buy() {
      router.push("/shop")
    },
    async select(e) {
      const item = this.items.find(i => i.id ==e)
      console.log("Checked: ",item.id, item.checked)
      if (item.checked) {
        //this.filter = item.id
        await this.store.commit(MUTATIONS.SET_EVENT, {eventId:item.id,providerId:item.provider_id});
      } else {
        //this.filter = 0;
        await this.store.commit(MUTATIONS.RESET_EVENT);
      }
    },
  },
  async beforeMount() {
    this.ds = await DataStorage.getInstance()
    const providerString = await this.ds.getItem("provider") || "[]"
    const providers = JSON.parse(providerString)
    const ticketString = await this.ds.getItem("ticket") || "[]"
    const tickets = JSON.parse(ticketString)
    const itemString = await this.ds.getItem("event") || "[]"
    const items = JSON.parse(itemString)
    const validItems = []
    for (let i=0; i < items.length; i++){
      // cechk if we have any tickets on this event
      const tick = tickets.find(t => t.event_id == items[i].id) || 0
      console.log("Tick: ",tick)
      if (tick == 0) continue
      const pid = items[i].provider_id
      const name = (providers.find(p => p.id == pid)).name
      //console.log("i: ",i,", id: ",id, ", name: ",name)
      items[i].provider = name
      items[i].checked = 0
      validItems.push(items[i])
    }
    this.items = validItems
    
    await this.store.commit(MUTATIONS.RESET_EVENT)
    //console.log("befMount: ",items,validItems)
  },
  computed: {
    selIitems() {
      // https://v3.vuejs.org/guide/computed.html#computed-properties
      //console.log("Filter on:", this.filter,"length: ",this.items.length)
      console.log("computed: ",this.items, this.items.length)
      const filter = this.store.state.selection.eventId
      console.log("Filter on:", filter,"length: ",this.items.length)
      const i = []
      if (filter == 0) {
        this.items.forEach(item => { 
          item.checked = 0
          i.push(item)
        })
      } else {
        const item = this.items.find(x => x.id == filter) || 0
        if (i != 0)
        item.checked = 1
        i.push(item)
      }
      /*
      this.items.forEach(item => { 
        //console.log(item)
        //if ((this.filter == 0) || (item.category_id == this.filter)) 
        //if ((this.filter == 0) || (item.id == this.filter)) 
        if ((filter == 0) || (item.id == filter)) 
          i.push(item)
        })
        */
      return i
    },
    hasEvent() {
      return (this.store.state.selection.eventId != 0)
    },
  },
    // store
  setup() {
    const store = useStore();
    return { store };
  },
}
); 
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

button {
  margin-top: .5rem;
}


.eventItem {
  display: inline-block;
  width:85%;
}

.eventCheck {
  display: inline-block;
  /* width:10%; */ 
  margin-left:5%;
  margin-bottom: 2em;
}
input.eventCheck  {
  width:1em;
}

</style>

