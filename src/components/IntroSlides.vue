<template>

  <ion-slides v-if="ionSwiper" ref="slider" class="slides" pager="true" 
    :options="slideOpts" 
    @ionSlidesDidLoad="slidesLoaded" 
    >
    <ion-slide>
     <ion-img class="frontImg" src="/assets/img/front/1.jpg"></ion-img>
    </ion-slide>
    <ion-slide>
     <ion-img class="frontImg" src="/assets/img/front/2.jpg"></ion-img>
    </ion-slide>
    <ion-slide>
     <ion-img class="frontImg" src="/assets/img/front/3.jpg"></ion-img>
    </ion-slide>
  </ion-slides>

  <!-- vue swiper   -->
  
  <swiper v-if="!ionSwiper" class="vueslides" v-bind="updated"
    :slides-per-view="1"
    :space-between="50"
    :autoplay="{delay: 2500, disableOnInteraction: false,}"
    :pagination="{ clickable: false }"
    :loop="false"
    :centeredSlides="true"
    @swiper="onSwiper"
    @slideChange="onSlideChange"
    effect='slide'
    enabled="true"
  >
    <swiper-slide class="vueslide" ><img class="frontImg" src="/assets/img/front/1.jpg"></swiper-slide>
    <swiper-slide class="vueslide"><img class="frontImg" src="/assets/img/front/2.jpg"></swiper-slide>
    <swiper-slide class="vueslide"><img class="frontImg" src="/assets/img/front/3.jpg"></swiper-slide>
  </swiper>


</template>

<script> 
import { IonImg, IonSlides, IonSlide } from '@ionic/vue';

import { defineComponent } from 'vue';

// vue swiper 
import SwiperCore, { Navigation, Pagination, Autoplay, EffectFlip } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/vue';
 // install Swiper modules
SwiperCore.use([Navigation, Pagination, Autoplay, EffectFlip]);

  // Import Swiper styles
//import 'swiper/swiper.scss';
import 'swiper/swiper-bundle.min.css';


// maybe check https://thewebdev.info/2021/01/10/add-a-swiper-carousel-into-a-vue-3-app-with-swiper-6/
// and https://swiperjs.com/vue
// https://www.youtube.com/watch?v=-Qm-tG4Kt9s

export default defineComponent({
  name: "IntroSlides",
  watch: {
    '$route' (to, from) {
      //console.log('Rout update2',to,from);
      if (from.path == "/intro") {
        if (this.sliderLoaded) {
          this.$refs.slider.$el.stopAutoplay().then(() => {console.log("Autoplay stopped")})
        }
        console.log('Leaving');
      }
      if (to.path == "/intro") {
        console.log('Now on intro');
        if (this.sliderLoaded) {
          this.$refs.slider.$el.startAutoplay().then(() => {console.log("Autoplay started")})
        }
        if (this.swiper) {
          console.log('swiper init');
          this.swiper.init()
          this.swiper.autoplay.stop()
          const s = this.swiper
          setTimeout(() => {s.autoplay.start()},1000)
          //this.swiper.autoplay.start()
          this.updated++
        }
      }
    }
  },
  components: { IonSlides, IonSlide, IonImg,
   Swiper, SwiperSlide,
  },
  data : function() {
    return {
        sliderLoaded:false,
        swiper: null,
        updated: 0,
    }
  },
  methods: {
      onSwiper(swiper) {
        console.log("Swiper set")
        this.swiper = swiper
      },
      onSlideChange() {
        console.log('slide change');
      },

    /*
    @ionSlideDidChange="slideChanged($event)" 
    @ionSlideNextStart="slideNext($event)" 
    slideChanged(event) {
      console.log("Changed:",event)
    },
    slideNext(event) {
      console.log("Next:",event)
    }
    */
    async slidesLoaded() {
      console.log("Slides Loaded") //,event.target)
      // autoplay is set on option, don't need to do this here again
      //await this.$refs.slider.$el.startAutoplay()
      //await this.$refs.slider.$el.lockSwipes(true)
      this.sliderLoaded = true
      /*
      console.log("Swiper",swiper)
      const index = await this.$refs.slider.$el.getActiveIndex()
      console.log("Index",index)
      //await this.$refs.slider.$el.slideTo(2)
      */
      // await this.$refs.slider.$el.startAutoplay()


      
      /*
      const swiper = this.$refs.slider
      const s1 = new IonSlides.Slides().getSwiper()
      console.log("Swiper",s1)
      console.log("slider: ",swiper)
      console.log("swiper: ",swiper.swiper)
      console.log("swiper: ",swiper.getSwiper)
      console.log("swiper: ",this.swiper)
      const s1 = swiper.getSwiper()
      const s2 = IonSlides.getSwiper()
      */
    },
  },
  setup(){
    const slideOpts = {
          initialSlide: 0,
          // multiple per view
          //slidesPerView: 2,
          spaceBetween: 20,
          speed: 1200,
          //watchSlidesProgress: true,
          autoplay: true, // 2500
          loop: false,
        }
      const ionSwiper = true
      return { slideOpts, ionSwiper }
    }
});

</script>

<style scoped>

.slides {
}

.vueslide {
  max-height:300px;
}

.vueslides {
}

.frontImg {
  max-width:400px;
  padding-left:1em;
  padding-right:1em;
  
}

</style>

