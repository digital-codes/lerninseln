<template>

    <ion-item class="ticket">
    <ion-label
      >
      {{costinfo}},  {{cost}} € 
      </ion-label>
    <ion-label slot="end"
      >
      <ion-button  @click="less()"
      >-
      </ion-button>
      {{count}}
      <ion-button  @click="more()"
      >+
      </ion-button>
      </ion-label>
      </ion-item>

</template>

<script> 
import { IonItem, IonLabel, IonButton } from '@ionic/vue';

// could use thumbnails or avatars in place of icons.
/*
https://ionicframework.com/docs/api/thumbnail
<ion-thumbnail>
    <img src="https://gravatar.com/avatar/dba6bae8c566f9d4041fb9cd9ada7741?d=identicon&f=y">
  </ion-thumbnail>

https://ionicframework.com/docs/api/avatar

*/

import { defineComponent } from 'vue'; 

export default defineComponent({
  props: ['cost','costinfo',"limit","count"],
  data () {
    return {
      //count: count0,
    }
  },
  components: { IonItem, IonLabel, IonButton},
  emits: ["costUpdate"],
  methods: {
    less() {
      //this.count? this.count-- : this.count;
      //this.$emit("costUpdate",this.count * this.cost )
      //this.$emit("costUpdate",-(this.cost +10))
      this.$emit("costUpdate",-1 )//this.count)
    },
    more() {
      //this.count < this.limit ? this.count++ : this.count;
      //this.$emit("costUpdate",this.cost + 10)
      this.$emit("costUpdate",1) //this.count)
    }
  },
});

</script>

<style scoped>

.ticket {
  text-align: left;
  white-space: normal;
  margin-top: 0;
  margin-bottom: 0;
}
</style>

