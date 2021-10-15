<template>

    <div v-if="hasCodes">
    <ion-card-header>
      <ion-card-subtitle>Deine Codes</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>
    <!--
    <ul class="list">
      <li v-for="item in getCodes"  :key="item.qrsrc" class="listItem">

            <QrShow 
              :id="item.id" 
              :date="item.date" 
              :time="item.time" 
              :provider="item.provider" 
              :event="item.event" 
              :count="item.count" 
              :qrsrc="item.qrsrc" 
              :title="item.title" 
              :info="item.info" 
              >
              </QrShow>
      </li>
    </ul>
    -->
          <!--
                props: ["title","date","time","provider","id","count","qrscr","event","info"],
            -->
            <QrShow v-for="item in getCodes"  :key="item.eventId" class="listItem"  
              :date="item.date" 
              :time="item.time" 
              :provider="item.provider" 
              :count="item.count" 
              :qrsrc="item.qrsrc" 
              :title="item.title" 
              :info="item.info" 
              :url="item.url" 
              :disabled="item.scored"
              @scoring="score($event,item.eventId)"
              >
              </QrShow>


    </ion-card-content>
    </div>
    <div v-else>
    <ion-card-header>
      <ion-card-subtitle>Keine Codes!</ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>
    <p>Hast Du noch nichts gebucht?</p>
    <ion-img src="/assets/img/codes/qr1.jpg"></ion-img>
    <p>Bitte wähle eine Veranstaltung aus unseren <a href="/map">Angeboten</a></p>
    </ion-card-content>


    </div>

</template>

<script> 

import { defineComponent,  } from 'vue'; // ref for modal

// load all data from server and write to database
import DataStorage from "../services/dstore";
// https://next.vuex.vuejs.org/guide/composition-api.html#accessing-state-and-getters

import { useStore, Selection, MUTATIONS } from '../services/quickStore';

import QrShow from '@/components/QrShow.vue'

// define dummy code or note ...
const DUMMY_CODE = false
const DUMMY_ITEM = {
  title:"Title",
  provider: "Provider",
  eventId:5,
  date:"2021-08-29",
  time:"12:00",
  count:1,
  url:"https://www.cern.ch",
  info:"Dummy",
  qrsrc:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAeoAAAHqAQMAAADxo595AAAABlBMVEUAAAD///+l2Z/dAAAAAnRS\
TlP//8i138cAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAH4SURBVHic7dtLbsQgDIBhSzkAR+LqORIH\
GIkJYPNIK2Uy4+x+FlFL+OjGAmxSyT81gcPhcDgcDofD4XA4HA6Hw+FwOBwO/5on0RbTlo+HSN7D\
0b/L8au2DQ735fpOYn61oSJBXxx98xA43I83VOK1ztHGTH1w+LO8DN1F10s4/HG+2bYcyqIJhz/J\
W2csjyZH0C5D4HAvLtpi0sidHtrgcF++vtFl8Tgb7icEh/vxGrQh9yi1A2JdL/P1Bg2Hf8f7gHE2\
rBnJhystHH6X9804WLmvFv7imC1kONyX16Aty2JDpdxns/2/S8Phv/Jl/AhffZGz3XTA4d68Vlpq\
lIq+HYWXc+TC4Q5cpluNCUmLZjjcnSdLN1r2Ma2XZY7wWv8WHO7Ct6wZiYWv7tclaFsyfJHLwOF3\
uZ3+/uzIoxizajj8Z56stNxTkLZyaiDD4Q/wtelXyCL23UD/CQ535FrVs/rKS5Phfl68LrzA4Xd5\
b2kNVTsvXmTQcPh9bh8KzNdo5dGu1pL97w8c7slHztGLzCNU0xqvcLgX1wAtv+2jyNc36HiaAw73\
43arNl3j7nWKqy9e4PDveX+UMXq1oUPgcGfeOjX70AsNu+T4/FgIh3/ORVtM87JYu/SACIc78/sN\
DofD4XA4HA6Hw+FwOBwOh8PhcDj8VnsDg+O/lIZXxKIAAAAASUVORK5CYII="
}

export default defineComponent({
  components: { QrShow,  },
  data: function() {
    return {
      isZoomed: false,
    }
  },
  methods:{
    score(s,e){
      console.log("Scoring:",s,e)
    }
  },
  computed: {
    hasCodes() {
      if (DUMMY_CODE)
        return true
      else
        return (this.store.state.qrcode.length > 0)
    },
    getCodes() {
      if (DUMMY_CODE)
        return [DUMMY_ITEM]
      else
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
