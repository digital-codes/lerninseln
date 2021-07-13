<template>

  <ion-slides ref="slider" class="slides" pager="true" 
    :options="slideOpts" 
    @ionSlidesDidLoad="slidesLoaded($event)" 
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


</template>

<script> 
import { IonSlides, IonSlide } from '@ionic/vue';

import { IonImg,  } from '@ionic/vue';

import { defineComponent, ref } from 'vue';


// maybe check https://thewebdev.info/2021/01/10/add-a-swiper-carousel-into-a-vue-3-app-with-swiper-6/
// and https://swiperjs.com/vue
// https://www.youtube.com/watch?v=-Qm-tG4Kt9s

export default defineComponent({
  name: "IntroSlides",
  components: { IonSlides, IonSlide, IonImg },
  data : function() {
    return {
        slideOpts : {
          initialSlide: 0,
          // multiple per view
          //slidesPerView: 2,
          spaceBetween: 20,
          speed: 1200,
          //watchSlidesProgress: true,
          autoplay: true, // 2500
          loop: false,
        },
        swiper:""
    }
  },
  methods: {
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
    async slidesLoaded(event) {
      console.log("Slides Loaded") //,event.target)
      console.log("Options",this.slideOpts) //,event.target)

      const swiper = await this.$refs.slider.$el.getSwiper()
      console.log("Swiper",swiper)
      const index = await this.$refs.slider.$el.getActiveIndex()
      console.log("Indexr",index)
      await this.$refs.slider.$el.slideTo(2)
      await this.$refs.slider.$el.startAutoplay()

      this.swiper = swiper

      
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
    const myslides = ref(null)

      return {myslides}
    }
});

</script>

<style scoped>

.slides {
}

.frontImg {
  max-width:400px;
}

</style>

