<template>

    <div v-if="hasCodes">
    <ul class="list">
      <li v-for="item in getCodes"  :key="item.qrsrc" class="listItem">
            <h2 class="qrlabel">{{item.title}}</h2>
            <p> {{ item.date }}  {{ item.time }} </p>
            <p> {{ item.provider }} {{ item.count }} Person(en)</p>
            <img 
              :src="item.qrsrc"  
              :class="{ 'qrcode': isZoomed }"
              @click="zoomQr(item)" 
            >
      </li>
    </ul>
    </div>
    <div v-else>
      <h1 class="headline">Du hast noch keine Codes</h1>
      Buche eine Veranstaltung ...
    </div>

</template>

<script> 

import { modalController } from '@ionic/vue';
import { defineComponent,  } from 'vue'; // ref for modal

// load all data from server and write to database
import DataStorage from "../services/dstore";
// https://next.vuex.vuejs.org/guide/composition-api.html#accessing-state-and-getters

import { useStore, Selection, MUTATIONS } from '../services/quickStore';

import QrModal from '@/components/QrModal.vue'


export default defineComponent({
  components: {  },
  data: function() {
    return {
      isZoomed: false,
    }
  },
  methods:{
    hasCodes() {
      return this.store.state.qrcode.length > 0
    },
    zoom(e) {
      console.log(e)
      this.isZoomed = !this.isZoomed
      console.log("zoomed:",this.isZoomed)
    },
    async zoomQr(data) {
      // see also https://stackoverflow.com/questions/65740559/cant-close-the-modal-in-ionic-vue-5-5-2
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
            provider: data.provider
          },
        })
      await modal.present()
      await modal.onDidDismiss();
      console.log('Modal dismissed');
    },
  },
  computed: {
    getCodes() {
      return this.store.state.qrcode
    }
  },
  // store
  setup() {
    const store = useStore();
    return { store };
  },
}); 
</script>

<style scoped>

.headline {

}

img {
  width:64px;
  height:64px;
}

.qrcode {
  width:90%;
  height:90%;
}

.w {
  width:100%;
}

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

</style>
