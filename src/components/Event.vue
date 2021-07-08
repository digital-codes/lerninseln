<template>

 <ion-item-group>
    <ion-item-divider>
      <ion-label class="event"><h2>{{title}}, ID {{id}}</h2></ion-label>
    </ion-item-divider>
    <ion-item color="light">
      <ion-icon :icon="getIcon()" slot="start" class="eventIcon"/>
      <ion-label position="fixed" class="eventDate">
      <div>{{date}}</div>
      <div>{{time}}</div>
      </ion-label>
      <ion-label class="eventMore">
      <a href="https://www.cern.ch" target="_blank">Mehr ...</a>
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
import { IonItemGroup, IonIcon, IonItem, IonItemDivider, IonLabel } from '@ionic/vue';
import { walk, warning, wifi, wine } from 'ionicons/icons';

// could use thumbnails or avatars in place of icons.
/*
https://ionicframework.com/docs/api/thumbnail
<ion-thumbnail>
    <img src="https://gravatar.com/avatar/dba6bae8c566f9d4041fb9cd9ada7741?d=identicon&f=y">
  </ion-thumbnail>

https://ionicframework.com/docs/api/avatar

*/

import { defineComponent } from 'vue'; 

export default defineComponent({
  props: ['title','text',"date",'time','id','icon'],
  components: {IonItemGroup, IonIcon, IonItem, IonItemDivider, IonLabel},
  methods: {
    getIcon(){
      //console.log("Icon id:",parseInt(this.icon))
      switch (parseInt(this.icon)) {
        case 1:
          return wifi;
        case 2:
          return wine;
        case 3:
          return walk;
        default:
          return warning;
      } 
    }
  },
  setup() {
    return {
      walk, 
      wifi,
      warning,
      wine,
    }
  }
});

</script>

<style scoped>

h2 {
  padding-bottom: .2rem;
}

.event {
  text-align: left;
  white-space: normal;
  margin-top: 0;
  margin-bottom: 0;
}

.eventIcon {
  margin-right: .5em;
}

.eventDate {
  text-align: left;
  /* with position: fixed adjust size */
  flex-basis: 110px;
  margin-top: 0;
  margin-bottom: 0;
}

.eventMore {
  text-align: left;
  /* with position: fixed adjust size */
  display: inline-block;
}

.footer {
  height: 1rem;
  background-color: primary;

}
</style>

