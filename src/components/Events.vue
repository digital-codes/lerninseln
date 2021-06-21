<template>

<ul class="list">
  <li v-for="item in items"  :key="item.id" class="listItem">
        <Event 
          :date=item.date 
          :text=item.text 
          :id=item.id 
          @click="click(item.id)"
          ></Event>
  </li>
</ul>

<div v-if="modalOpen" class="modal" :selection=selection >
  <div>
    <h2>Ausgewählt</h2>
    <p>
    {{ selection.date }} 
    <br>
    {{ selection.text }} 
    </p> 
    <Ion-button  @click="modalOpen = false">
      Fertig
    </Ion-button>
  </div>
</div>

</template>

<script> 

import { IonSlides, IonSlide, } from '@ionic/vue';

import { defineComponent } from 'vue'; 
import Event from '@/components/Event.vue';

export default defineComponent({
  components: {Event},
  data: function() {
    return {
      items: [{
      'date': '2021-06-21 12:00',
      'text': 'Fischertechnik',
      "id":1,
    }, {
      'date': '2021-06-25 10:00',
      'text': 'Schach',
      "id":2,
    }, {
      'date': '2021-07-05 09:30',
      'text': 'Band-Projekt',
      "id":3,
    }],
    modalOpen:false,
    selection:0,
    }
  },
  methods:{
    click(e) {
      console.log(e,this.items[e-1].text)
      this.selection = this.items[e-1]
      this.modalOpen = true;
    }
  }
}); 
</script>

<style scoped>


.list {
  list-style: none;
  padding: 0 2% 0 2%;
}

.listItem {
  padding-top: .3rem;
  padding-bottom: 1rem;
  border-bottom: solid 1px;
  border-bottom-color: #444;
}

h2 {
  padding-bottom: .2rem;
}

button {
  margin-top: .5rem;
}

.modal {
  position: absolute;
  top: 0; right: 0; bottom: 0; left: 0;
  background-color: rgba(0,0,0,.5);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.modal div {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: white;
  /*
  width: 300px;
  height: 300px;
  */
  width: 100%;
  height: 100%;
  padding: 5px;
}


</style>

