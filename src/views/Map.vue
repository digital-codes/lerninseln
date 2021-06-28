<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Map
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>

      <ion-card slot="fixed" >
      <ion-card-content>

      <Leaf></Leaf>

      </ion-card-content>
      </ion-card>

    <!--ion-content :fullscreen="true"-->
    <ion-content >

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


    </ion-content>
  </ion-page>
</template>

<script lang="js">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonCard, IonCardContent } from '@ionic/vue';
import Leaf from "@/components/Leaf.vue";
//import Events from '@/components/Events.vue';
import Events from '@/components/Events.vue';

import { useStore, Todo, Selection, MUTATIONS, ACTIONS } from '../store';

// database
import {initDataStore, setDataStore, getDataStore } from "../datastore";

export default  {
  name: 'Map',
  components: { IonHeader, IonToolbar, IonTitle, IonContent, IonCard, IonCardContent, IonPage, Leaf ,Events, },
  async beforeMount() {
    this.store.commit(MUTATIONS.RESET_EVENT);
  },
  setup() {
    const store = useStore();
    return { store };
    // mounted
  },
}
</script>
