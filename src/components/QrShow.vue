<template>

  <div class="qrContainer">
    <h2 class="qrlabel">{{title}}</h2>
    <p class="qrinfo"> {{ date }}  {{ time }} </p>
    <p class="qrinfo"> {{ provider }} {{ count }} Person(en)</p>
    <img 
        :src="qrsrc"  
        :class=" {'qrcode': isZoomed, 'qrthumb':true } "
        @click="zoomQr()" 
    >
    <div class="qrscore">

      <div class="qrstars">
      <ion-range min="0" max="5" step="1" snaps="true" ticks="true" pin="true" disabled="false" 
        @ionChange="starsSelected($event)"
        ref="stars"
        >
       <ion-avatar class="smallStar" slot="start">
          <img src="/assets/img/scores/star.svg">
        </ion-avatar>
       <ion-avatar class="bigStar" slot="end">
          <img src="/assets/img/scores/star.svg">
        </ion-avatar>
      </ion-range>
      </div>

      <!-- 
      <div class="qrpros">
       <ion-select name="pros" placeholder="Positiv" @ionChange="prosSelected($event)">
          <ion-select-option value="0">Nichts</ion-select-option>
          <ion-select-option value="1">Team</ion-select-option>
          <ion-select-option value="2">Raum</ion-select-option>
          <ion-select-option value="3">Ausstattung</ion-select-option>
          <ion-select-option value="4">Programm</ion-select-option>
        </ion-select>
      </div>
      <div class="qrcons">
       <ion-select name="cons" disabled="true" placeholder="Negativ" @ionChange="consSelected($event)">
          <ion-select-option value="0">Nichts</ion-select-option>
          <ion-select-option value="1">Team</ion-select-option>
          <ion-select-option value="2">Raum</ion-select-option>
          <ion-select-option value="3">Ausstattung</ion-select-option>
          <ion-select-option value="4">Programm</ion-select-option>
        </ion-select>
      </div>
       -->
      <div class="qrbutton">
       <ion-button class="qrbutton1" @click="sendScore()">
        Bewerten
        </ion-button>
      </div>
    </div>
  </div>

</template>

<script> 

import { defineComponent,  } from 'vue'; // ref for modal

import { modalController,IonSelect, IonSelectOption, IonRange, IonAvatar, IonButton  } from '@ionic/vue';
import QrModal from '@/components/QrModal.vue'


export default defineComponent({
    name: "QrShow",
    props: ["title","date","time","provider","id","count","qrsrc","event","info"],
  components: { IonRange,  IonAvatar,
    //IonSelect, IonSelectOption,
    IonButton,
   },
  data: function() {
    return {
      score: {stars:0,
              pros: 0,
              cons: 0,
              },
      isZoomed: false,
    }
  },
  methods:{
    prosSelected(e){
      console.log("Pros: ",e.target.value)
      this.score.pros = e.target.value
    },
    consSelected(e){
      console.log("Cons: ",e.target.value)
      this.score.cons = e.target.value
    },
    starsSelected(e){
      console.log("Stars: ",e.target.value)
      //console.log("Event target: ",e.target)
      this.score.stars = e.target.value
    },
    sendScore(){
      console.log("Scoring:", this.score.stars,this.score.pros,this.score.cons)
    },
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
            info: this.info,
          },
        })
      await modal.present()
      await modal.onDidDismiss();
      console.log('Modal dismissed');
    },
  },
  setup(){ //props) {
    //console.log("Props:",props)
  },
}); 
</script>

<style scoped>

.headline {

}

.qrthumb  {
  max-width:96px;
  max-height:96px;
}

h2 {
  padding-bottom: .2rem;
}

.qrcode {
  width:90%;
  height:90%;
}

/* eval */
.qrContainer {
  width:100%;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-gap: 1rem;
  text-align:left;
}
.qrlabel {
  grid-column: 1 / span 2;
}
.qrinfo {
  grid-column: 1 / span 2;
}
.qrthumb {
  grid-column: 1;
}
.qrscore {
  grid-column: 2 / span 2;
  height: 96px; /* same as qrthumb *"
}

.qrstars {
  /*grid-column: 1 / span 2;*/
  /*display: block;*/
}

.qrbutton {
  text-align: center;
}

.smallStar {
  width:32px;
  height:32px;
  margin-top:8px;
  opacity: 70 %;  /* problem on android ... translated to 1% */
}

.bigStar {
  width:48px;
  height:48px;
}

ion-range {
  padding:0;
  --knob-size: 20px;
  /* same height as qrthumb */
  --height: 54px;
}


.qrpros {
  /*grid-column: 3;*/
  /*
  display: inline-block;
  margin-right: 1rem;
  */
}
.qrcons {
  /*grid-column: 4;*/
  /*
  display: inline-block;
  */
}

ion-select {
  /* Applies to the value and placeholder color 
  color: #545ca7;

  /* Set a different placeholder color 
  --placeholder-color: #971e49;

  /* Set full opacity on the placeholder 
  --placeholder-opacity: 1;
  */
  --padding-start:0;
  --padding-top:0;
  --padding-bottom:0;  
}

</style>

