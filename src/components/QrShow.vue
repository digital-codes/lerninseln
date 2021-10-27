<template>

  <div class="qrtop">
    <ion-item-group class="qrContainer">
    <ion-item lines="none" class="qritem">
      <h2 class="qrlabel">{{title}}</h2>
    </ion-item>
    <ion-item lines="none" class="qritem">
      <p class="qrinfo"> {{ date }}  {{ time }} </p>
    </ion-item>
    <ion-item lines="none" class="qritem">
      <p class="qrinfo"> {{ provider }} {{ count }} Person(en)</p>
    </ion-item>
    <ion-item lines="none" class="qritem">
    <img 
        :src="qrsrc"  
        :class=" {'qrcode': isZoomed, 'qrthumb':true } "
        @click="zoomQr()" 
    >
    </ion-item>
   
    </ion-item-group>

    <ion-item-group class="scoreContainer">
      <ion-item class="cancel">
       <ion-button class="qrbutton" :disabled="isCancelDisabled()" @click="cancel()">
        Abmelden
        </ion-button>
    </ion-item>
    <ion-item lines="none" class="qritem">
      <div class="qrStars">
      <ion-range min="0" max="5" step="1" snaps="true" ticks="true" pin="true" :disabled="isScoreDisabled()" 
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
    </ion-item>
    <ion-item lines="none" class="qritem">
       <ion-button class="qrbutton" :disabled="isScoreDisabled()" @click="sendScore()">
        Bewerten
        </ion-button>
    </ion-item>
    </ion-item-group>

  </div>


</template>

<script> 

import { defineComponent,  } from 'vue'; // ref for modal

import { modalController,IonSelect, IonSelectOption, IonRange, IonAvatar, IonButton  } from '@ionic/vue';
import QrModal from '@/components/QrModal.vue'


export default defineComponent({
    name: "QrShow",
    props: ["title","date","time","provider","id","count","qrsrc","info","disabled"],
    emits:["scoring","cancel"],
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
      this.$emit("scoring",this.score)
    },
    cancel(){
      console.log("Cancelling not finished")
      this.$emit("cancel")
    },
    isScoreDisabled(){
      if (this.disabled) return true
      // cehck date and time and
      const dt = new Date()
      const date = dt.toISOString().split("T")[0]
      const tm = dt.toLocaleTimeString('de-DE')
      const time = tm.split(":")[0] + ":" + tm.split(":")[1]
      console.log("date: ",date, time)

      return (date < this.date) || (time < this.time) 
    },
    isCancelDisabled(){
      console.log("Cancelling must be fixed")
      if (this.disabled) return true
      // cehck date and time and
      const dt = new Date()
      const date = dt.toISOString().split("T")[0]
      const tm = dt.toLocaleTimeString('de-DE')
      const time = tm.split(":")[0] + ":" + tm.split(":")[1]
      console.log("date: ",date, time)

      return (date >= this.date) // cancel up to the day before 
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

/* eval */
/*
.qrContainer {
  width:100%;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-gap: 1rem;
  text-align:left;
}
*/
.qrtop {
  width:100%;
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  grid-gap: 1rem;
  text-align:left;
}

.qrContainer {
  grid-column: 1 / span 3;
  width:100%;
  display: block;
  text-align:left;
}

.scoreContainer {
  grid-column: 4 / span 2;
  width:100%;
  display: block;
  text-align:left;
}

.qrcode {
  width:70%;
  height:70%;
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
  margin-left: auto;
  margin-right: auto;
}

.qritem {
  --min-height:unset;
}

.qrStars {
  width: 100%;
  /* padding to be fixed */
  margin-top: 1.0rem;
  padding-top: 1.0rem;
}

/* make stars media dependent: width and margin,  
small: 16/32, 5
large: 24/48 , 10*/
.smallStar {
  width:16px;
  height:16px;
  margin: 0 5px 0 0;
  opacity: 70 %;  /* problem on android ... translated to 1% */
}

.bigStar {
  width:32px;
  height:32px;
  margin: 0 0 0 5px;
}

ion-range {
  padding:0;
  --knob-size: 1.2rem;
  /* same height as qrthumb */
  /* --height: 54px; */
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

