<template>

  <div v-for="item in items"  :key="item.id" class="listItem">
        <Event 
          :date=item.date 
          :time=item.time 
          :title=item.title 
          :text="item.text" 
          :id=item.id 
          @click="open(item.id)"
          ></Event>
  </div>
  <ion-button>button</ion-button>

<!--
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
-->
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
    selection:0,
    tickets: 0
    }
  },
  methods:{
    async loadItems() {
      await initDataStore()
      const e = await getDataStore("event") || "[]"
      const items = JSON.parse(e);
      return this.items = items
    },
    open(e) {
      console.log(e,this.items[e-1].text)
      this.selection = this.items[e-1]
      this.tickets = 0
    },
    async beforeMount() {
      console.log("Eventlist")
      //await initDataStore()
      //const items = await getDataStore("event") || []
      // this.items = JSON.parse(items);
      /*
      const items = [
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1},
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1},
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1},
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1},
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1}
      ]
      this.items = items
      console.log(items)
      */
    },

  },
  computed: {
    items1: function() {
      // see https://www.digitalocean.com/community/tutorials/vuejs-iterating-v-for
      console.log("Eventlist")
      //await initDataStore()
      //const items = await getDataStore("event") || []
      // this.items = JSON.parse(items);
      const items = loadItems()
      /*const items = [
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1},
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1},
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1},
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1},
        {"title":"123","text":"wee32","date":"e2ee21","time":"d323e","id":1}
      ]*/
      console.log(items)
      return  items
    }
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

