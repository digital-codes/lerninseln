
// storage 
//https://github.com/ionic-team/ionic-storage#sqlite-installation
import { Storage } from '@ionic/storage';
import { Drivers } from '@ionic/storage';
import * as CordovaSQLiteDriver from 'localforage-cordovasqlitedriver';

// check https://github.com/capacitor-community/sqlite
// and https://www.npmjs.com/package/vue-sqlite-hook
// for sqlite. needs more stuff than existin (2021-06-26)

// data store
const dataStore = new Storage({
      name: '__lerninseln',
      driverOrder: [CordovaSQLiteDriver._driver, Drivers.IndexedDB, Drivers.LocalStorage]
    });

//let inititalized = false

export async function initDataStore() {
  const dbDrv = await dataStore.defineDriver(CordovaSQLiteDriver)
  console.log("Datastore set up with ",dbDrv)
  try {
    const dbMagic = await dataStore.get("magic")
    console.log("Datastore exists")
    //await dataStore.clear()
    //console.log("Datastore cleared")
    await dataStore.set("magic",dbMagic + 1)
  } catch {
    console.log("Error datastore")
    console.log("Creating new DataStore")
    await dataStore.create();
    await dataStore.clear()
    await dataStore.set("magic",1)
  }
  return dataStore;
}

export function getDataStore(key: string) {
  return dataStore.get(key);
}

export function setDataStore(key: string, value: string | number) {
  return dataStore.set(key,value);
}

