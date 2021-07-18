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
      <!--span @click="popState(true)" >Mehr ...</span-->
      </ion-label>
    </ion-item>
    <ion-item-divider>
      <ion-label  class="event"><p>{{text}}</p></ion-label>
    </ion-item-divider>
 </ion-item-group>

  <!--
 <ion-popover
    :is-open="popOpen"
    css-class="eventPop"
    :translucent="true"
    @didDismiss="popState(false)"
  >
    <Popover>
    <iframe 
      width="400px" 
      height="40%" 
      overflow="overflow-scroll" 
      src="https://www.cern.ch" 
      >
      </iframe>
    </Popover>
  </ion-popover>
  -->

  <!--
  <div>
  <h2>{{date}}</h2>
  <p>{{text}}</p>
  </div>
  -->
</template>

<script lang="ts"> 
import { IonItemGroup, IonIcon, IonItem, IonItemDivider, IonLabel,
  IonPopover,
 } from '@ionic/vue';
import { walk, warning, wifi, wine } from 'ionicons/icons';

import Popver from './popover.vue'

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
  components: {IonItemGroup, IonIcon, IonItem, IonItemDivider, IonLabel,
    },
  data () {
    return {
      popOpen: false,
    }
  },
  methods: {
    popState(v: boolean) {
      this.popOpen = v
      console.log("Close:",v)
    },

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

.eventPop {
  width: 100%;
}

.popover-content {
  width:600px;
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

