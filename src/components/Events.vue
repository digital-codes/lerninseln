<template>

  <ion-button @click="toggle()">Toggle</ion-button>
  <div v-for="item in selIitems"  :key="item.id" class="listItem">
        <Event 
          :date=item.date 
          :time=item.time 
          :title=item.title 
          :text=item.provider_id 
          :id=item.id 
          @click="open(item.id)"
          ></Event>
  </div>

</template>

<script> 

import { IonButton } from '@ionic/vue';
// database
import {initDataStore, setDataStore, getDataStore } from "../datastore";

import { defineComponent } from 'vue'; 
import Event from '@/components/Event.vue';

export default defineComponent({
  name: "Events",
  components: {Event, IonButton},
  props: { 
    ticketLimit: {
      type: Number,
      default: 3
    }
  },
  data () {
    return {
    items : [],
    filter : 0,
    selection:0,
    tickets: 0
    }
  },
  methods:{
    open(e) {
      console.log(e,this.items[e-1].text)
      this.selection = this.items[e-1]
      this.tickets = 0
    },
    toggle(){
      this.filter = this.filter == 0?1:0;
    }
  },
  async beforeMount() {
    await initDataStore()
    const items = await getDataStore("event") || "[]"
    this.items = JSON.parse(items)
    //console.log("befMount: ",items)
  },
  computed: {
    selIitems() {
      // https://v3.vuejs.org/guide/computed.html#computed-properties
      console.log("Filter on:", this.filter,"length: ",this.items.length)
      const i = []
      this.items.forEach(item => { 
        console.log(item)
        if ((this.filter == 0) || (item.category_id == this.filter)) 
          i.push(item)
        })
      return i
    },
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

.modal {
  position: absolute;
  top: 0; right: 0; bottom: 0; left: 0;
  background-color: rgba(0,0,0,.5);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.modal div {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: white;
  /*
  width: 300px;
  height: 300px;
  */
  width: 100%;
  height: 100%;
  padding: 5px;
}

.eventAccess button {
  display: inline-block;
}

</style>

