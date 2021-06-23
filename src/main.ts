import { createApp } from 'vue'
import App from './App.vue'
import router from './router';

import { IonicVue } from '@ionic/vue';

/* Core CSS required for Ionic components to work properly */
import '@ionic/vue/css/core.css';

/* Basic CSS for apps built with Ionic */
import '@ionic/vue/css/normalize.css';
import '@ionic/vue/css/structure.css';
import '@ionic/vue/css/typography.css';

/* Optional CSS utils that can be commented out */
import '@ionic/vue/css/padding.css';
import '@ionic/vue/css/float-elements.css';
import '@ionic/vue/css/text-alignment.css';
import '@ionic/vue/css/text-transformation.css';
import '@ionic/vue/css/flex-utils.css';
import '@ionic/vue/css/display.css';

/* Theme variables */
import './theme/variables.css';


// with vuex ---------
/*
// maybe helpful: https://ionicframework.com/blog/managing-state-in-vue-with-vuex/
import { createStore, Store } from 'vuex';
import { InjectionKey } from 'vue';

export interface State {
  count: number;
}

export const key: InjectionKey<Store<State>> = Symbol()

//const state: State;

// Create a new store instance.
const store = createStore({
  state () {
    return {
      count: 0
    }
  },
  mutations: {
    increment (state) {
      (state as State).count++
    }
  }
})
*/
// see https://next.vuex.vuejs.org/guide/typescript-support.html#simplifying-usestore-usage
// with store.ts ...
// in a vue component

import { store, key } from './store'


// --------------------


const app = createApp(App)
  .use(IonicVue)
  .use(router)
  .use(store, key);
  
router.isReady().then(() => {
  app.mount('#app');
});