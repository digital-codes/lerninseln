<template>

<ul class="list">
  <li v-for="item in items"  :key="item.id" class="listItem">
        <h2>{{item.date}}</h2>
        <p>{{item.text}}</p>
  </li>
</ul>


</template>

<script> 

import { defineComponent } from 'vue'; 

export default defineComponent({
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
    }
  },
  methods:{
    zoom(e) {
      console.log(e)
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

</style>

