<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Tickets
        <img alt="logo" height="40" style="vertical-align:middle"  src="/assets/img/logo.png" > 
        </ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content >
      <ion-card>

      <p 
        :evnt="store.state.selection.eventId" 
        :sel="store.state.selection" 
        :todo="store.state.todos[0]" 
        >
        ID: {{ evnt }} SEL: {{ sel }} T: {{ todo }}
      </p>

      <ion-card-content>

        <BookNew></BookNew>

      </ion-card-content>
      </ion-card>

    </ion-content>

  </ion-page>
</template>

<script lang="ts">
import { IonPage, IonHeader, IonToolbar, IonTitle, IonContent,IonCard, IonCardContent  } from '@ionic/vue';
import BookNew from '@/components/BookNew.vue';

import { useStore, Todo, Selection, MUTATIONS } from '../store';

export default  {
  name: 'Tickets',
  components: { BookNew, IonHeader, IonToolbar, IonTitle, IonContent, IonPage,IonCard, IonCardContent  },
    // store
  setup() {
    const store = useStore();
    return { store };
  },

}
</script>
