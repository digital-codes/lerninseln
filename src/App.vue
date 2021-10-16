<template>
  <ion-app>
    <ion-router-outlet />
  </ion-app>
</template>

<script lang="ts">
import { IonApp, IonRouterOutlet } from '@ionic/vue';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'App',
  components: {
    IonApp,
    IonRouterOutlet
  }
});
</script>


<style>
/*
https://ionicframework.com/docs/theming/themes
*/
/*
KA Colors:
#048500   green
#f7f9f5   light green
#404040   gray
#000000   black

*/

:root {
  --ion-color-primary:#048500;
  --ion-color-secondary: #ff00ff;
  --ion-background-color:#f7f9f5;
  --ion-text-color: #404040; /* so far, affects only toolbar header */
  --ion-font-family:roboto,sans-serif;
  --ion-tab-bar-color:#404040;  /* default color for icon and label */
  --ion-tab-bar-color-selected:#048500; /* selected  color for icon and label */
}

</style>
