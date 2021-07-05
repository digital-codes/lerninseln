// from https://www.javascripttuts.com/using-vue-as-an-angular-alternative-for-ionic-the-services/

// load data
// cors: https://web.dev/cross-origin-resource-sharing/
//const axios = await import ('axios');
//import axios from 'axios';
//const baseUrl = "https://lerninseln.ok-lab-karlsruhe.de/simpleSrv.php"
const baseUrl = "http://localhost:9000/simpleSrv.php"
const baseGetUrl = baseUrl + "?table=";
//const config = { headers: {'Access-Control-Allow-Origin': '*'}}
const getConfig = { headers: {'Access-Control-Allow-Origin': '*'}}
const postConfig = { headers: {'Access-Control-Allow-Origin': '*',
  'content-type': 'application/json'
  }
}


export default class DataFetch {

  private static fetch: any;
  private static instance: DataFetch;
  
    static async getInstance() {
      if (!DataFetch.instance) {
        console.log("New Axios instance")
        DataFetch.instance = new DataFetch();
        DataFetch.fetch = await import ("axios");
      }
      return DataFetch.instance;
    }
    
    async getTable(table: string) {
      console.log("Axios get")
      const url = baseGetUrl + table
      let result: any
      await DataFetch.fetch.get(url,getConfig)
      .then((response: { data: any }) => {
        result = response.data
      })
      .catch((error: any) => {
          console.log("Axios error:", error);
          result = []
        });
        console.log("Response status: ",result.status)
        return result.data
      }

      async post(data: Record<string,any>) {
        console.log("Axios post")
        const url = baseUrl
        //const url ="http://localhost:9000/simpleSrv.php"
        const result: {status: number; code: number; payload: any} = 
          {status:0,code:0,payload:{}}
        await DataFetch.fetch.post(url,data, postConfig)
        .then((response: { data: any }) => {
          //console.log("Response:",response.data);
          result.payload = response.data
          result.status = 1
          result.code = 0
        })
        .catch((error: any) => {
            console.log("Axios error:", error);
            result.payload = error.status_text
            result.status = 0
            result.code = error.status

          });
          return result
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
