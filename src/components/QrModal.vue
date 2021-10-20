<template>
  <ion-page>
  <ion-content fullscreen class="ion-padding qrmodal">
    <h2 class="qrlabel">{{title}}</h2>
    <p> {{ date }} - {{ time }} </p>
    <p> {{ provider }} - {{ count }} Person(en)</p>
    <img 
      :src="qrsrc" 
      class="qrimage"
    >
  </ion-content>
  <ion-text  v-if='info > ""' color="secondary" class="info">{{ info }}</ion-text>
  <ion-button @click="close()">Schliessen</ion-button>
  </ion-page>
</template>

<script>
//import { IonContent, IonHeader, IonTitle, IonToolbar } from '@ionic/vue';
import { IonPage, IonContent, IonButton, IonText, modalController } from '@ionic/vue';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'QrModal',
  props: {
    title: { type: String, default: 'Super Modal' },
    qrsrc: {type: String },
    date: {type: String},
    time: {type: String},
    provider: {type: String},
    count: {type: Number},
    info: {type: String, default: ""},
    class: {type: String, default: "modal"}
  },
  data() {
    return {
    }
  },
  methods: {
    close() {
      console.log("closing qr modal")
      modalController.dismiss();
    }
  },
  components: { IonContent, IonPage, IonButton },
  /*
  // https://dev.to/amitgurbani/creating-a-modal-in-ionic-vue-2fe5
  setup() {
    const closeModal = () => {
      modalController.dismiss();
    };

    return { closeModal };
  },
  */
});
</script>

<style scoped>
.info {
  text-align: center;
  color: rgb(0,0, 255);
  z-index:10;
}
.qrmodal {
  width:100%;
  --background: unset;
}
.qrimage {
  width:100%;
  margin:0;
}
</style>


