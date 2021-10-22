<template>
    <ion-card>
    <ion-card-header>
    <ion-card-subtitle>Filter
    </ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>

          <FilterItem v-for="n in 5" :key="n" 
            :name=labels[n-1]
            :icon=icons[n-1]
            :check=check[n-1]
            @filter="onFilter(n-1)"
          />


    </ion-card-content>
  </ion-card>

</template>

<script lang="ts">
import {IonCard, IonCardContent, IonCardHeader,IonCardSubtitle } from '@ionic/vue';
import { defineComponent } from 'vue';

import FilterItem from '@/components/FilterItem.vue';


import { 
  volumeMuteOutline,
  wifiOutline,
  medkitOutline,
  buildOutline,
  peopleOutline,
 } from 'ionicons/icons';

const icons=[ volumeMuteOutline,
  wifiOutline,
  medkitOutline,
  buildOutline,
  peopleOutline]

const labels = ["F1","F2","F3","F4","F5"]

export default defineComponent ({
  name: "Filter",
  components: { IonCard, IonCardContent, IonCardHeader, IonCardSubtitle, FilterItem
  },
  data () {
    return {
      fs1: false,
      check: [false,false,false,false,false],
      update: 0,
      icons: icons,
      labels: labels
    }
  },
  methods: {
    onFilter(x: number) {
        console.log("Filter event: ",x)
        for (let i=0;i<this.check.length;i++){
          this.check[i] = (i == x)
        }
        //console.log(this.check)
    },
  },
  setup() {
    return {
      volumeMuteOutline,
      wifiOutline,
      medkitOutline,
      buildOutline,
      peopleOutline,
    }
  },

});
</script>

<style scoped>

.hdr {
  color: unset;  
}

ion-card-content {
  padding: 0;
  text-align: center;

}

ion-card-header {
  padding: 0;
}

ion-card-subtitle {
  text-align:center;
}

.label {

}

.inactive {
  --background: #50c8ff;
}

</style>
