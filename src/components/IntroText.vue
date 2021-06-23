<template>
    <ion-card>
    <ion-card-header>
      <ion-card-title>Lerninseln</ion-card-title>
      <ion-card-subtitle>Eine Initiative der Stadt Karlsruhe</ion-card-subtitle>
    </ion-card-header>

    <ion-card-content>
        Die Idee der „Lerninseln“ ist es, diese Orte in einer interaktiven Karte von Karlsruhe zu
        verorten und sichtbar zu machen. Schulische, sowie außerschulische Lernorte aufzuzeigen, die
        die unterschiedlichste Angebote machen und das Lernen in der „realen Welt“ ermöglichen.
        Vor allem im MINT-Bereich gibt es bereits zahlreiche Initiativen und Lernorte in Karlsruhe, die
        durch die interaktive Karte deutlich sichtbarer und besser gefunden werden können.
    </ion-card-content>
  </ion-card>

      <ion-list>
        <ion-item @click="randomTodo()" v-for="todo in store.state.todos" :key="todo.id">
            <ion-label>{{ todo.title }}</ion-label>
        </ion-item>
      </ion-list>


</template>

<script lang="ts">
import {IonCard, IonCardContent, IonCardSubtitle, IonCardTitle } from '@ionic/vue';
import { defineComponent } from 'vue';

import { useStore, Todo, MUTATIONS, ACTIONS } from '../store';

export default defineComponent ({
  name: "IntroText",
  components: { IonCard, IonCardContent, IonCardSubtitle, IonCardTitle },
  // store
  setup() {
    const store = useStore();
    const randomTodo = () =>  store.dispatch(ACTIONS.ADD_RND_TODO);
    const complete = (todo: Todo) => {
      store.commit(MUTATIONS.DEL_TODO, todo);
    };
    //return { complete, store, randomTodo};
    console.log("store: ",store)
    return { complete, store, randomTodo};
  },

});
</script>

<style scoped>
#container {
  text-align: center;
}

#container strong {
  font-size: 20px;
  line-height: 26px;
}

#container p {
  font-size: 16px;
  line-height: 22px;
  color: #8c8c8c;
  margin: 0;
}

#container a {
  text-decoration: none;
}
</style>
