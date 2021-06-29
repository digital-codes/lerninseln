// from https://www.javascripttuts.com/using-vue-as-an-angular-alternative-for-ionic-the-services/

export default class CounterSingleton {
    counter: number = 0;
  
    private static instance : CounterSingleton;
  
    static getInstance() {
      if (!CounterSingleton.instance) {
        CounterSingleton.instance = new CounterSingleton();
      }
      return CounterSingleton.instance;
    }
  
    add() {
      this.counter++;
    }
  
    show() {
      console.log(this.counter);
    }
  }

/*
must set caller to JS 

in components:

<div>
<button @click="show">
    Show
</button>
<button @click="add">
    Add
</button>
</div>

import CounterSingleton from "../services/serv";

data: return {
    counterService: CounterSingleton.getInstance(),
    ...
}


methods: {
    show: function() {
        this.counterService.show();
    },
    add: function() {
        this.counterService.add();
    },
    ...
},


*/
