<template>
    <ion-card>
    <ion-card-header>
    <ion-card-subtitle>Filter
    </ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>

          <FilterItem v-for="n in 4" :key="n" 
            :name=labels[n-1]
            :icon=icons[n-1]
            :check=check[n-1]
            @filter="onFilter(n-1,$event)"
          />

    </ion-card-content>
  </ion-card>

</template>

<script lang="ts">
import {IonCard, IonCardContent, IonCardHeader,IonCardSubtitle } from '@ionic/vue';
import { defineComponent } from 'vue';

import FilterItem from '@/components/FilterItem.vue';

import { useStore, Filter, MUTATIONS } from '../services/quickStore';

import { 
  volumeMuteOutline,
  //wifiOutline,
  medkitOutline,
  constructOutline,
  peopleOutline,
 } from 'ionicons/icons';

const icons=[ volumeMuteOutline,
  //wifiOutline,
  medkitOutline,
  constructOutline,
  peopleOutline]

const labels = ["F1","F2","F3","F4"]

export default defineComponent ({
  name: "Filter",
  components: { IonCard, IonCardContent, IonCardHeader, IonCardSubtitle, FilterItem
  },
  data () {
    return {
      check: [false,false,false,false],
      update: 0,
      icons: icons,
      labels: labels,
      filterOff: true
    }
  },
  methods: {
    async onFilter(x: number, y: boolean) {
        console.log("Filter event: ",x,y,"Store filter: ",this.store.state.filter.catId)
        const filter = this.store.state.filter
        if (y) {
          for (let i=0;i<this.check.length;i++){
            this.check[i] = (i == x)
          }
          this.filterOff = false
          filter.catId = x + 1 // categories run from 1
          await this.store.commit(MUTATIONS.SET_FILTER,filter)
        } else {
          if (this.check[x]) {
            filter.catId = 0
            await this.store.commit(MUTATIONS.SET_FILTER,filter)
            this.filterOff = true
          }
        }
        //console.log(this.check)
    },
  },
  setup() {
    const store = useStore()
    return {
      store,
      volumeMuteOutline,
      //wifiOutline,
      medkitOutline,
      constructOutline,
      peopleOutline,
    }
  },

});
</script>

<style scoped>

.hdr {
  color: unset;  
}


ion-card {
  margin: 0;
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

ion-card-content {
  line-height: 1.2em;
}

ion-card-content .card-content-ios {
  padding-inline-start: 5px;
  padding-inline-end: 5px;
  padding-top: 5px;
  padding-bottom: 5px;
}


.label {

}

.inactive {
  --background: #50c8ff;
}

</style>
