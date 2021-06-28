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
  <ion-button @click="toggle()">Toggle</ion-button>

  <div v-for="item in selIitems"  :key="item.id" class="listItem">
        <Event 
          :date=item.date 
          :time=item.time 
          :title=item.title 
          :text=item.provider 
          :id=item.id  
          @click="open(item.id)"
          ></Event>
  </div>

</template>

<script> 

import { IonButton,  } from '@ionic/vue';
// database
import {initDataStore, setDataStore, getDataStore } from "../datastore";

import { useStore, Selection, MUTATIONS } from '../store';

import { defineComponent } from 'vue'; 
import Event from '@/components/Event.vue';

export default defineComponent({
  name: "Events",
  components: {Event, IonButton, },
  data () {
    return {
    items : [],
    providers: [],
    filter : 0,
    chk1: 1, 
    chk2: 0, 
    chk3: 1, 
    }
  },
  methods:{
    open(e) {
      const item = this.items[e-1]
      //console.log(e,item.id) // normally same ..
      this.store.commit(MUTATIONS.SET_EVENT, item.id);
    },
    toggle(){
      this.filter = this.filter == 0?1:0;
    }
  },
  async beforeMount() {
    await initDataStore ()
    const providerString = await getDataStore("provider") || "[]"
    const providers = JSON.parse(providerString)
    const itemString = await getDataStore("event") || "[]"
    const items = JSON.parse(itemString)
    for (let i=0; i < items.length; i++){
      const id = items[i].provider_id - 1
      const name = providers[id].name
      console.log("i: ",i,", id: ",id, ", name: ",name)
      items[i].provider = name
    }
    this.items = items
    
    this.store.commit(MUTATIONS.RESET_EVENT)
    //console.log("befMount: ",items)
  },
  computed: {
    selIitems() {
      // https://v3.vuejs.org/guide/computed.html#computed-properties
      console.log("Filter on:", this.filter,"length: ",this.items.length)
      const i = []
      this.items.forEach(item => { 
        //console.log(item)
        if ((this.filter == 0) || (item.category_id == this.filter)) 
          i.push(item)
        })
      return i
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

