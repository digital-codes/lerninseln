<template>
  <ion-page>
    <!--
    <ion-header>
      <ion-toolbar>
        <ion-title>Map
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>
    -->
      <ion-card slot="fixed" class="map">
      <ion-card-content>

      <Leaf></Leaf>

      </ion-card-content>
      </ion-card>

    <!--ion-content :fullscreen="true"-->
    <ion-content >
      <div ref="tab2" class="swiping">

      <ion-card >
      <ion-card-content>
        <Filter/>
      </ion-card-content>
      </ion-card >


      <ion-card >
      <ion-card-content>

      <Events></Events>
      <!--
      <div v-for="item in items"  :key="item.id" class="listItem">
            {{item.title}}
            <Event 
              :date=item.date 
              :time=item.time 
              :title=item.title 
              :text="item.text" 
              :id=item.id 
              @click="open(item.id)"
              ></Event>
      </div>
      -->
      </ion-card-content>
      </ion-card>

    </div>
    </ion-content>
  </ion-page>
</template>

<script lang="js">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonCard, IonCardContent } from '@ionic/vue';
import Leaf from "@/components/Leaf.vue";
//import Events from '@/components/Events.vue';
import Events from '@/components/Events.vue';

import Filter from '@/components/Filter.vue';

import { useStore, Todo, Selection, MUTATIONS, ACTIONS } from '../services/quickStore';

import { defineComponent, ref } from 'vue'; 

import { createGesture } from '@ionic/vue';
import router from "../router";


export default defineComponent( {
  name: 'Map',
  components: {  IonContent, IonCard, IonCardContent, IonPage, Leaf ,Events, Filter},
  methods : {
    onSwipe(detail) {
      const type = detail.type;
      const cx = detail.currentX;
      const dx = detail.deltaX;
      const vx = detail.velocityX;
      //console.log(type,cx,dx,vx)
      if ((type == "pan") && (dx > 100) && (vx > 1)) router.push("/intro")
      if ((type == "pan") && (dx < -100) && (vx < -1)) router.push("/shop")
    }
  },
  async beforeMount() {
    await this.store.commit(MUTATIONS.RESET_EVENT);
  },
  async mounted(){
    const gest = this.$refs.tab2 //ref();
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
  setup() {
    const store = useStore();
    return { store };
    // mounted
  },
})
</script>

<style scoped>
.map {
  margin-bottom:0;
}



</style>
