// from https://www.javascripttuts.com/using-vue-as-an-angular-alternative-for-ionic-the-services/

// storage 
//https://github.com/ionic-team/ionic-storage#sqlite-installation
import { Storage } from '@ionic/storage';
import { Drivers } from '@ionic/storage';
import * as CordovaSQLiteDriver from 'localforage-cordovasqlitedriver';

// check https://github.com/capacitor-community/sqlite
// and https://www.npmjs.com/package/vue-sqlite-hook
// for sqlite. needs more stuff than existin (2021-06-26)

export default class DataStorage {

    private static storage: Storage;
    private static instance: DataStorage;

    static async getInstance() {
      if (!DataStorage.instance) {
        console.log("New DS instance")
        DataStorage.instance = new DataStorage();
        DataStorage.storage = new Storage({
          name: '__lerninseln', // name is not used for native DB ???
          driverOrder: [CordovaSQLiteDriver._driver, Drivers.IndexedDB, Drivers.LocalStorage]
        })
        await DataStorage.storage.defineDriver(CordovaSQLiteDriver)
        try {
          const dbMagic = await DataStorage.storage.get("magic")
          console.log("Datastore exists")
          //await dataStore.clear()
          //console.log("Datastore cleared")
          await DataStorage.storage.set("magic",dbMagic + 1)
        } catch {
          console.log("Error datastore")
          console.log("Creating new DataStore")
          await DataStorage.storage.create();
          // FIXME check if clear needed!
          //await DataStorage.storage.clear()
          await DataStorage.storage.set("magic",1)
        }
      } else {
        console.log("DS instance exists")
      }
      return DataStorage.instance;
    }
    
    getItem(key: string) {
      return DataStorage.storage.get(key);
    }
    
    setItem(key: string, value: string | number) {
      return DataStorage.storage.set(key,value);
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
