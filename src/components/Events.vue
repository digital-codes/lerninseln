<template>

<ul class="list">
  <li v-for="item in items"  :key="item.id" class="listItem">
        <Event 
          :date=item.date 
          :time=item.time 
          :title=item.title 
          :text="item.text" 
          :id=item.id 
          @click="open(item.id)"
          ></Event>
  </li>
  <ion-button>button</ion-button>
</ul>

</template>

<script> 

import { IonButton, IonItem } from '@ionic/vue';
// database
import {initDataStore, setDataStore, getDataStore } from "../datastore";

import { defineComponent } from 'vue'; 
import Event from '@/components/Event.vue';

export default defineComponent({
  components: {Event, IonButton},
  props: { 
    ticketLimit: {
      type: Number,
      default: 3
    }
  },
  data: function() {
    return {
    items : [],
    selection:0,
    tickets: 0
    }
  },
  methods:{
    open(e) {
      console.log(e,this.items[e-1].text)
      this.selection = this.items[e-1]
      this.modalOpen = true;
      this.tickets = 0
    },
    access(id,e) {
      console.log(id,e)
      const avail = this.items[id-1].avail
      if (e) {
        if (avail - this.tickets > 0) {
          if (this.tickets < this.ticketLimit)
            this.tickets++
        }
      } else {
        if (this.tickets) this.tickets --
      }
    },
    async beforeMount() {
      console.log("Eventlist")
      await initDataStore()
      const items = await getDataStore("event")
      console.log(items)
      this.items = JSON.parse(items);
    },

  },
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

