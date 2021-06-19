<template>
  <ion-page>
    <ion-tabs>
      <ion-tab-bar slot="bottom">
        <ion-tab-button tab="intro" href="/intro">
          <ion-icon :icon="triangle" />
          <ion-label>Intro</ion-label>
        </ion-tab-button>
          
        <ion-tab-button tab="topics" href="/topics">
          <ion-icon :icon="ellipse" />
          <ion-label>Topics</ion-label>
        </ion-tab-button>
        
        <ion-tab-button tab="map" href="/map">
          <ion-icon :icon="square" />
          <ion-label>Map</ion-label>
        </ion-tab-button>
        
        <ion-tab-button tab="dates" href="/dates">
          <ion-icon :icon="square" />
          <ion-label>Dates</ion-label>
        </ion-tab-button>
        
        <ion-tab-button tab="tickets" href="/tickets">
          <ion-icon :icon="square" />
          <ion-label>Tickets</ion-label>
        </ion-tab-button>
      </ion-tab-bar>
    </ion-tabs>
  </ion-page>
</template>

<script lang="ts">
import { IonTabBar, IonTabButton, IonTabs, IonLabel, IonIcon, IonPage } from '@ionic/vue';
import { ellipse, square, triangle } from 'ionicons/icons';

export default {
  name: 'Tabs',
  components: { IonLabel, IonTabs, IonTabBar, IonTabButton, IonIcon, IonPage },
  setup() {
    return {
      ellipse, 
      square, 
      triangle,
    }
  }
}
</script>
