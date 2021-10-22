<template>
    <ion-card>
    <ion-card-header>
    <ion-card-subtitle>Filter
    </ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>

          <FilterItem v-for="n in 5" :key="n" v-model="update"
            name=labels[n-1]
            :icon=icons[n-1]
            :check=check[n-1]
            @filter="onFilter(n-1,$event)"
          />

        <!--
          <FilterItem :key=check[0]
            name="F1"
            :icon=volumeMuteOutline
            :check=check[0]
            @filter="onFilter(0,$event)"
          >
          </FilterItem>

          <FilterItem  :key=check[1]
            name="F2"
            :icon=wifiOutline
            :check=check[1]
            @filter="onFilter(1,$event)"
          >
          </FilterItem>

          <FilterItem  :key=check[2]
            name="F3"
            :icon=medkitOutline
            :check=check[2]
            @filter="onFilter(2,$event)"
          >
          </FilterItem>

          <FilterItem  :key=check[3]
            name="F4"
            :icon=buildOutline
            :check=check[3]
            @filter="onFilter(3,$event)"
          >
          </FilterItem>

          <FilterItem  :key=check[4]
            name="F5"
            :icon=peopleOutline
            :check=check[4]
            @filter="onFilter(4,$event)"
          >
          </FilterItem>
          -->

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
      check: [0,0,0,0,0],
      update: 0,
      icons: icons,
      labels: labels
    }
  },
  methods: {
    onFilter(x: number,y: number) {
      console.log("Filter event: ",x,y)
      if (y) {
        const a = [0,0,0,0,0]
        a[x] = 1
        this.check = a
      } else 
        this.check[x] = 0
      console.log(this.check)
      this.update++
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
