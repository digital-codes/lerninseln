<template>
  <div id="mapId">

  <l-map style="height:30vh"
    :zoom="zoom"
    :center="center"
    :max-zoom="maxZoom"
    @update:center="centerUpdate"
    @update:zoom="zoomUpdate"
    @click="addPoint" 
  >

 <l-tile-layer 
        :url="url" 
        :attribution="attribution"
  >
  </l-tile-layer>

  <l-marker v-for="item in markers" :key="item.id" :lat-lng="item.latlng"
      @l-add="$event.target.openPopup()"
  >
      <l-popup :content="item.content"></l-popup>
      <l-icon v-if="item.iconOptions.iconSize[0] != 0"
        :iconUrl="item.iconOptions.iconUrl"
        :iconSize="item.iconOptions.iconSize"
        >
      </l-icon>

  </l-marker>


  
  </l-map>
  
  </div>

  <p  :dbMagic="dbMagic" >DB  {{ dbMagic }}</p>

</template>

<script>

// leaf2vue docs: https://vue2-leaflet.netlify.app/examples/feature-group.html

// DON'T load Leaflet components here!
// Its CSS is needed though, if not imported elsewhere in your application.
import "leaflet/dist/leaflet.css"
import { LMap, LGeoJson,LMarker, LPopup, LTileLayer, LIcon } from "@vue-leaflet/vue-leaflet";
import { defineComponent } from 'vue';

import { useStore, Selection, MUTATIONS, ACTIONS } from '../store';

// storage 
/*
//https://github.com/ionic-team/ionic-storage#sqlite-installation
import { Storage } from '@ionic/storage';
import { Drivers } from '@ionic/storage';
import * as CordovaSQLiteDriver from 'localforage-cordovasqlitedriver';
*/
// database
import {initDataStore, setDataStore, getDataStore } from "../datastore";

// ----------------------- 

//let startPnt = [49.004,  8.403]

export default defineComponent ({
  name: "Leaf",
  components: {
    LMap,
    LTileLayer,
    LPopup,
    LMarker,
    LIcon,
    
  },
  data() {
    return {
      dbMagic:0,
      geokey:0,
      zoom:13,
      maxZoom:17,
      //center1: latlng(49.004,  8.403),
      //center2: geojsonOptions.latlng(49.004,  8.403),
      //center: this.geojsonOptions.latlng(49.004,  8.403),
      center: [49.004,  8.403],
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution:
        '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      markers : [],
      geojson: {},
      geojsonOptions: {
        /*
        "crs": {
          "type": "name",
          "properties": {
            "name": "EPSG:4326"
          }
        },
        */
      },
    };
  },
  methods : {
    highLight(id) {
      this.markers[id].iconOptions.iconUrl = "https://placekitten.com/50/100"
      this.markers[id].iconOptions.iconSize = [50,50]
      // see https://vdcrea.gitlab.io/vue-leaflet/#licon
    },
    zoomUpdate(zoom) {
      this.currentZoom = zoom;
    },
    centerUpdate(center) {
      this.currentCenter = center;
    },
    addPoint(e) {
      //console.log(`You clicked Add Point from`,e,"Type e",typeof(e),"Orig: ",e.originalEvent,"Target ",e.target,"Type target ",typeof(e.target))
      if (undefined == e.originalEvent) return
      this.startPnt[0] += .0003
      this.startPnt[1] += .0003
      console.log("Start:", this.startPnt)
      const content = '<div class="popInfo">234<br>Click for more<p><a href="https://cern.ch" target="_blank">Link</a></p></div>'
      this.markers.push({"id":this.geokey,"latlng":this.startPnt,"content":content,
                "iconOptions":{"iconUrl":"https://placekitten.com/50/100","iconSize":[30,30]},
      })
      this.geokey += 1
      this.highLight(3)
      //console.log(this.markers)
    },
    async initialize() {
      const storedProviders = await getDataStore("provider") || "[]"
      const pp = JSON.parse(storedProviders)
      if (pp.length > 0) {
        //this.providers.forEach(p => {
        pp.forEach(p => {
          const ll = JSON.parse(p.latlon)
          const pnt =  [ll.lat,ll.lon]
          //console.log(ll)
          const content = '<div class="popInfo"><h3>' + p.name + "</h3>" + p.info + '</div>'
          const iconUrl = "https://placekitten.com/50/100"
          const iconSize = [0,0]
          const iconOptions = {"iconUrl":iconUrl,"iconSize":iconSize}
          this.markers.push({"id":p.id,"latlng":pnt,
          "content":content  + "<p>"+ p.id + "</p>",
          "iconOptions":iconOptions
          })
        })
        //this.markers[98].iconUrl = "https://placekitten.com/50/100"
      } else {
        for (let i=0;i<5;i++) {
          const pnt =  [this.startPnt[0] += .0005 * i, this.startPnt[1] += .0005]
          const content = '<div class="popInfo">234<br>Click for more<p><a href="https://cern.ch" target="_blank">Link</a></p></div>'
          this.markers.push({"id":this.geokey,"latlng":pnt,"content":content,
          "iconOptions":{"iconUrl":"","iconSize":[0,0]},
          })
          this.geokey += 1
        }
      }
      /*
      this.markers[98].icon = "123"
      this.markers[97].icon = "123"
      this.markers[96].icon = "124"
      */
    }
  },
  async beforeMount() {

    // HERE is where to load Leaflet components!
    //const { map, marker, tileLayer, markerLayer, LayerGroup, latLng } = await import("leaflet/dist/leaflet-src.esm");
    const { latLng } = await import("leaflet/dist/leaflet-src.esm");
    //const { LMarker, LPopup, LTileLayer } = await import("leaflet/dist/leaflet-src.esm");

    const kaLat = {
      "center": 49.004,
      "min": 48.96,
      "max": 49.0391
    };
    const kaLon = {
      "center": 8.403,
      "min": 8.325,
      "max": 8.49
    };


    // And now the Leaflet circleMarker function can be used by the options:
    //this.geojsonOptions.pointToLayer = (feature, latLng) =>
     // circleMarker(latLng, { radius: 8 });
    //this.geojsonOptions.latlng = (lat, lon) => latLng(lat,lon);
    this.latLng = (lat, lon) => latLng(lat,lon);

    this.startPnt = [49.004,  8.403]
    this.mapIsReady = true;

    await initDataStore()
    this.dbMagic = await getDataStore("magic")
 
    this.initialize();
  },
  // store
  setup() {
    const store = useStore();
    return { store};
  },

});

/*
https://leafletjs.com/reference-1.7.1.html#icon
iconurl not set in options

var CustomIcon = L.Icon.extend({
   options: {
        iconUrl: './images/hotel.png',
        shadowUrl: './images/shadow.png',
        iconSize: new L.Point(32, 32),
        opacity: 0.5,
        //shadowSize: new L.Point(68, 95),
        iconAnchor: new L.Point(16, 16),
        popupAnchor: new L.Point(0, -18)
      }
    });

*/


</script>

<style scoped>
  .popInfo {
    font-weight: bold;
  }
  .popInfo h3{
    font-size: 1.3rem;
  }
</style>