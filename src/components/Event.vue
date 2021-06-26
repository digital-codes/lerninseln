<template>

 <ion-item-group>
    <ion-item-divider>
      <ion-label class="event"><h2>{{title}}</h2></ion-label>
    </ion-item-divider>
    <ion-item color="light">
      <ion-icon :icon="icon" slot="start"></ion-icon>
      <ion-button  color="tertiary" slot="end">X</ion-button>
      <ion-label position="fixed" class="eventDate">
      <div>{{date}}</div>
      <div>{{time}}</div>
      </ion-label>
    </ion-item>
    <ion-item-divider>
      <ion-label  class="event"><p>{{text}}</p></ion-label>
    </ion-item-divider>
 </ion-item-group>

  <!--
  <div>
  <h2>{{date}}</h2>
  <p>{{text}}</p>
  </div>
  -->
</template>

<script> 
import { IonItemGroup, IonIcon, IonItem, IonLabel } from '@ionic/vue';
import { walk, warning, wifi, wine } from 'ionicons/icons';

import { defineComponent } from 'vue'; 

export default defineComponent({
  props: ['title','text',"date",'time','id','icon'],
  components: {IonItemGroup, IonIcon, IonItem, IonLabel},
});

</script>

<style scoped>

h2 {
  padding-bottom: .2rem;
}

.event {
  text-align: left;
  white-space: normal;
}
.eventDate {
  text-align: left;
  /* with position: fixed adjust size */
  flex-basis: 150px;
}

.footer {
  height: 1rem;
  background-color: primary;

}
</style>

