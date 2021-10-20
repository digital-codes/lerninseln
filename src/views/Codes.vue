<template>
  <ion-page>
    <!--
    <ion-header>
      <ion-toolbar>
        <ion-title>Codes
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>
      -->

    <ion-content >
    <div ref="tab4" class="swiping">
      <ion-card >
      <!--
      <ion-card-header>
        <ion-card-title>Deine QR Codes</ion-card-title>
      </ion-card-header>
      -->
        <BookShow></BookShow>

      </ion-card>

    </div>
    </ion-content>

  </ion-page>
</template>

<script lang="js">
import { IonPage,  IonTitle, IonContent,IonCard } from '@ionic/vue';
import BookShow from '@/components/BookShow.vue';

import { defineComponent, ref } from 'vue'; 

import { createGesture } from '@ionic/vue';
import router from "../router";

export default  defineComponent({
  name: 'Codes',
  components: { BookShow, IonPage,IonCard,   },
  methods : {
    onSwipe(detail) {
      const type = detail.type;
      const cx = detail.currentX;
      const dx = detail.deltaX;
      const vx = detail.velocityX;
      //console.log(type,cx,dx,vx)
      if ((type == "pan") && (dx > 100) && (vx > 1)) router.push("/shop")
      //if ((type == "pan") && (dx < -100) && (vx < -1)) router.push("/shop")
    }
  },
  async mounted(){
    const gest = this.$refs.tab4 //ref();
    // current path:
    //const r = router.currentRoute.value.path // value is important!
    //console.log("Current: ",r,"ref2:",gest)
    //setTimeout(function(){console.log(r.path)},2000)
    const gesture = createGesture({
      onMove: (detail) => { this.onSwipe(detail);},
      gestureName: 'swipe',
      el: gest,
    })
    gesture.enable();
  },
})
</script>


<style scoped>
.hdr {
  --color: --ka-text-color;  
}
.swiping {
  min-height:90%;
}

</style>
