<template>

    <ion-card class="w">
    <ion-card-header>
      <ion-card-title>Anzeigen</ion-card-title>
      <ion-card-subtitle>...</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>

  <ion-list>
    <ion-item v-for="item in items" :key="item.src">
      <ion-thumbnail slot="end" class="qrcode">
        <ion-img :src="item.src" @click="zoom(item.id)"></ion-img>
      </ion-thumbnail>
      <ion-label class="qrlabel">{{item.text}}</ion-label>
    </ion-item>
  </ion-list>

  <!--
  <div>
      <p>bla bla bla</p>
      <ion-label class="qrlabel">bla vla dsd</ion-label>
      <ion-img class="qrcode" src="/assets/img/qr1.png"></ion-img>
  </div>
  -->

    </ion-card-content>
  </ion-card>

</template>

<script> 

import { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, 
  IonList, IonItem, 
  IonLabel, IonImg, IonThumbnail,   
  } from '@ionic/vue';
import { defineComponent } from 'vue'; // ref for modal

export default defineComponent({
  components: { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, 
    IonList, IonItem,  
    IonLabel, IonImg,    IonThumbnail, 
  },
  data: function() {
    return {
      items: [{
      'text': '2021-06-21 12:00',
      'src': '/assets/img/qr1.png',
      "id":1,
    }, {
      'text': '2021-06-25 10:00',
      'src': '/assets/img/qr2.png',
      "id":2,
    }, {
      'text': '2021-07-05 09:30',
      'src': '/assets/img/qr3.png',
      "id":3,
    }],
    }
  },
  methods:{
    zoom(e) {
      console.log(e)
    }
  }
}); 
</script>

<style scoped>

.qrcode {

}

.w {
  width:100%;
}

</style>