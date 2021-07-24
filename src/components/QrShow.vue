<template>

    <h2>QrShow </h2>

    code: {{ title }}
    <h2 class="qrlabel">{{title}}</h2>
    <p> {{ date }}  {{ time }} </p>
    <p> {{ provider }} {{ count }} Person(en)</p>
    <img 
        :src="qrsrc"  
        :class="{ 'qrcode': isZoomed }"
        @click="zoomQr()" 
    >

</template>

<script> 

import { defineComponent,  } from 'vue'; // ref for modal

import { modalController } from '@ionic/vue';
import QrModal from '@/components/QrModal.vue'


export default defineComponent({
    name: "QrShow",
    props: ["title","date","time","provider","id","count","qrsrc","event"],
  components: {  },
  data: function() {
    return {
      isZoomed: false,
    }
  },
  methods:{
    zoom(e) {
      console.log(e)
      this.isZoomed = !this.isZoomed
      console.log("zoomed:",this.isZoomed)
    },
    async zoomQr() {
      // see also https://stackoverflow.com/questions/65740559/cant-close-the-modal-in-ionic-vue-5-5-2
      const modal = await modalController
        .create({
          component: QrModal,
          cssClass: 'my-custom-class',
          componentProps: {
            title: this.title,
            qrsrc:this.qrsrc,
            date: this.date,
            time: this.time,
            count: this.count,
            provider: this.provider,
            info: ""
          },
        })
      await modal.present()
      await modal.onDidDismiss();
      console.log('Modal dismissed');
    },
  },
  setup(props) {
    console.log("Props:",props)
    return {
      /*
      item: {
            title: props.title,
            qrsrc: props.qrsrc,
            date: props.date,
            time: props.time,
            count: props.count,
            provider: props.provider,
            info: ""
      }
      */
    }
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


h2 {
  padding-bottom: .2rem;
}

.qrcode {
  width:90%;
  height:90%;
}

.w {
  width:100%;
}

</style>
