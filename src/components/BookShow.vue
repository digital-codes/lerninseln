<template>

    <ion-card class="w">
    <ion-card-header>
      <ion-card-title>Anzeigen</ion-card-title>
      <ion-card-subtitle>Slide right to make reservation</ion-card-subtitle>
      <ion-card-subtitle>Click Thumbnail to zoom</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>


    <ul class="list">
      <li v-for="item in getCodes"  :key="item.id" class="listItem">
            <h2 class="qrlabel">{{item.text}}</h2>
            <img 
              :src="item.qrsrc"  
              :class="{ 'qrcode': isZoomed }"
              @click="zoom(item.id)" 
            >
      </li>
    </ul>


    </ion-card-content>
  </ion-card>

</template>

<script> 

import { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle,    
  } from '@ionic/vue';
import { defineComponent, reactive } from 'vue'; // ref for modal

// load all data from server and write to database
import DataStorage from "../services/dstore";
// https://next.vuex.vuejs.org/guide/composition-api.html#accessing-state-and-getters

import { useStore, Selection, MUTATIONS } from '../store';

const getCodes = reactive({ value: [] });

export default defineComponent({
  components: { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, 
  },
  data: function() {
    return {
      isZoomed: false,
      items: [{
      'text': '2021-06-21 12:00',
      'src': 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAeoAAAHqAQMAAADxo595AAAABlBMVEUAAAD///+l2Z/dAAAAAnRS\
TlP//8i138cAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAH4SURBVHic7dtLbsQgDIBhSzkAR+LqORIH\
GIkJYPNIK2Uy4+x+FlFL+OjGAmxSyT81gcPhcDgcDofD4XA4HA6Hw+FwOBwO/5on0RbTlo+HSN7D\
0b/L8au2DQ735fpOYn61oSJBXxx98xA43I83VOK1ztHGTH1w+LO8DN1F10s4/HG+2bYcyqIJhz/J\
W2csjyZH0C5D4HAvLtpi0sidHtrgcF++vtFl8Tgb7icEh/vxGrQh9yi1A2JdL/P1Bg2Hf8f7gHE2\
rBnJhystHH6X9804WLmvFv7imC1kONyX16Aty2JDpdxns/2/S8Phv/Jl/AhffZGz3XTA4d68Vlpq\
lIq+HYWXc+TC4Q5cpluNCUmLZjjcnSdLN1r2Ma2XZY7wWv8WHO7Ct6wZiYWv7tclaFsyfJHLwOF3\
uZ3+/uzIoxizajj8Z56stNxTkLZyaiDD4Q/wtelXyCL23UD/CQ535FrVs/rKS5Phfl68LrzA4Xd5\
b2kNVTsvXmTQcPh9bh8KzNdo5dGu1pL97w8c7slHztGLzCNU0xqvcLgX1wAtv+2jyNc36HiaAw73\
43arNl3j7nWKqy9e4PDveX+UMXq1oUPgcGfeOjX70AsNu+T4/FgIh3/ORVtM87JYu/SACIc78/sN\
DofD4XA4HA6Hw+FwOBwOh8PhcDj8VnsDg+O/lIZXxKIAAAAASUVORK5CYII=',
      "id":1,
    }, {
      'text': '2021-06-25 10:00',
      'src': '/assets/img/qr2.png',
      "id":2,
    }, {
      'text': '2021-07-05 09:30',
      'src': '/assets/img/qr3.png',
      "id":3,
    }],
    }
  },
  methods:{
    zoom(e) {
      console.log(e)
      this.isZoomed = !this.isZoomed
      console.log("zoomed:",this.isZoomed)
    },
    async loadCodesFromDb() {
      if (this.ds) {
        const qrString = await this.ds.getItem("qrcode") || "[]"
        const items = JSON.parse(qrString)
        getCodes.value = items
      }
    },
  },
  // see https://www.reddit.com/r/vuejs/comments/kkac96/async_computed_property_for_vue_3/
  computed: {
    getCodes() {
      this.loadCodesFromDb();
      return getCodes
      /*
      const qrString = await this.ds.getItem("qrcode") || "[]"
      const items = JSON.parse(qrString)
      return items
      */
    }
  },
  async beforeMount() {
    this.ds = await DataStorage.getInstance()
    const qrString = await this.ds.getItem("qrcode") || "[]"
    const items = JSON.parse(qrString)
    getCodes.value = items
    //this.items = items
  },
  // store
  setup() {
    const store = useStore();
    return { store };
  },
}); 
</script>

<style scoped>

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