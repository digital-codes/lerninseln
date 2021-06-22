<template>

<ul class="list">
  <li v-for="item in items"  :key="item.id" class="listItem">
        <Event 
          :date=item.date 
          :text=item.text 
          :id=item.id 
          @click="open(item.id)"
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
    <p>Freie Plätze: {{ selection.avail }} </p>

    <div class="eventAccess"  >
    <Ion-item>
      <Ion-button @click="access(selection.id, false)" v-model=tickets>-</Ion-button>
        Tickets: {{ tickets }}
      <Ion-button @click="access(selection.id, true)" v-model=tickets>+</Ion-button>
    </Ion-item>
    </div>

    <Ion-button  @click="presentActionSheet(selection)">
      Fertig
    </Ion-button>
  </div>
</div>

</template>

<script> 

import { IonButton, IonItem, actionSheetController } from '@ionic/vue';
import { rocket, trash,  } from 'ionicons/icons';

import { defineComponent } from 'vue'; 
import Event from '@/components/Event.vue';

export default defineComponent({
  components: {Event, IonButton},
  props: { 
    ticketLimit: {
      type: Number,
      default: 3
    }
  },
  data: function() {
    return {
      items: [{
      'date': '2021-06-21 12:00',
      'text': 'Fischertechnik',
      'avail': 1,
      "id":1,
    }, {
      'date': '2021-06-25 10:00',
      'text': 'Schach',
      'avail': 10,
      "id":2,
    }, {
      'date': '2021-07-05 09:30',
      'text': 'Band-Projekt',
      'avail': 0,
      "id":3,
    }],
    modalOpen:false,
    selection:0,
    tickets: 0
    }
  },
  methods:{
    open(e) {
      console.log(e,this.items[e-1].text)
      this.selection = this.items[e-1]
      this.modalOpen = true;
      this.tickets = 0
    },
    access(id,e) {
      console.log(id,e)
      const avail = this.items[id-1].avail
      if (e) {
        if (avail - this.tickets > 0) {
          if (this.tickets < this.ticketLimit)
            this.tickets++
        }
      } else {
        if (this.tickets) this.tickets --
      }
    },
    purchase(id,ticks) {
      console.log("Buy: ",id, ticks)
    },
    async presentActionSheet(sel) {
      this.modalOpen = false;
      if (this.tickets == 0) {
        console.log("No tickets ordered",sel.id)
        return
      }
      const actionSheet = await actionSheetController
        .create({
          header: 'Buchung',
          buttons: [
            {
              text: 'Bestätigen',
              icon: rocket,
              handler: () => {
                this.purchase(sel.id,this.tickets)
              }
            },
            {
              text: 'Abbrechen',
              icon: trash,
              role: 'cancel',
              handler: () => {
                console.log('Cancel clicked')
              },
            },
          ],
        });
      await actionSheet.present();
      const { role } = await actionSheet.onDidDismiss();
      console.log('onDidDismiss resolved with role', role);
    },
  },
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

.eventAccess button {
  display: inline-block;
}

</style>

