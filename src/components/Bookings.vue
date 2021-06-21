<template>

 <ion-slides pager="true" :options="slideOpts">
    <ion-slide>
    <BookNew></BookNew>
    </ion-slide>

    <ion-slide>
    <BookShow></BookShow>
    </ion-slide>

  </ion-slides>

</template>

<script> 

import { IonSlides, IonSlide, } from '@ionic/vue';
import BookNew from '@/components/BookNew.vue';
import BookShow from '@/components/BookShow.vue';

import { defineComponent } from 'vue';

export default defineComponent({
  components: { IonSlides, IonSlide, BookNew, BookShow },
  setup() {
    // Optional parameters to pass to the swiper instance. See http://idangero.us/swiper/api/ for valid options.
    const slideOpts = {
      initialSlide: 0,
      speed: 400
    };
    return { slideOpts }
  }
}); 
</script>

