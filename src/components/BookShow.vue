<template>

    <ion-card class="w">
    <ion-card-header>
      <ion-card-title>Anzeigen</ion-card-title>
      <ion-card-subtitle>Slide right to make reservation</ion-card-subtitle>
      <ion-card-subtitle>Click Thumbnail to zoom</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>


    <ul class="list">
      <li v-for="item in items"  :key="item.id" class="listItem">
            <h2 class="qrlabel">{{item.text}}</h2>
            <img 
              :src="item.src"  
              :class="{ 'qrcode': isZoomed }"
              @click="zoom(item.id)" 
            >
      </li>
    </ul>


    </ion-card-content>
  </ion-card>

</template>

<script> 

import { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle,    
  } from '@ionic/vue';
import { defineComponent } from 'vue'; // ref for modal

export default defineComponent({
  components: { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, 
  },
  data: function() {
    return {
      isZoomed: false,
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
      this.isZoomed = !this.isZoomed
      console.log("zoomed:",this.isZoomed)
    }
  }
}); 
</script>

<style scoped>

img {
  width:64px;
  height:64px;
}

.qrcode {
  width:90%;
  height:90%;
}

.w {
  width:100%;
}

.list {
  list-style: none;
  padding: 0 2% 0 2%;
}

.listItem {
  padding-top: .3rem;
  padding-bottom: 1rem;
  border-bottom: solid 1px;
  border-bottom-color: #444;
}

h2 {
  padding-bottom: .2rem;
}

</style>