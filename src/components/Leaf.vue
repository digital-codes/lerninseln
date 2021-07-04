<template>
  <div id="mapId">

  <l-map style="height:40vh" 
    :zoom="zoom"
    :center=selCenter 
    :max-zoom="maxZoom"
    @update:center="centerUpdate"
    @update:zoom="zoomUpdate"
  >

 <l-tile-layer 
        :url="url" 
        :attribution="attribution"
  >
  </l-tile-layer>

  <!--
  <l-marker v-for="item in markers" :key="item.id" :lat-lng="item.latlng"
  -->
  <l-marker v-for="item in selIitems" :key="item.id" :lat-lng="item.latlng"
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

</template>

<script>

// leaf2vue docs: https://vue2-leaflet.netlify.app/examples/feature-group.html

// DON'T load Leaflet components here!
// Its CSS is needed though, if not imported elsewhere in your application.
import "leaflet/dist/leaflet.css"
import { LMap, LGeoJson,LMarker, LPopup, LTileLayer, LIcon,  } from "@vue-leaflet/vue-leaflet";
import { defineComponent } from 'vue';

import { useStore, Selection, MUTATIONS, ACTIONS } from '../services/quickStore';

// storage 
/*
//https://github.com/ionic-team/ionic-storage#sqlite-installation
import { Storage } from '@ionic/storage';
import { Drivers } from '@ionic/storage';
import * as CordovaSQLiteDriver from 'localforage-cordovasqlitedriver';
*/
// database
import DataStorage from "../services/dstore";


// ----------------------- 

//let startPnt = [49.004,  8.403]

export default defineComponent ({
  name: "Leaf",
  watch: {
  '$route' (to, from) {
    //console.log('Rout update2',to,from);
    if (to.path == "/map") {
      console.log('Now on map');
      this.updated++
      //this.$map.invalidateSize()
      }
    }
  },
  components: {
    LMap,
    LTileLayer,
    LPopup,
    LMarker,
    LIcon,    
  },
  data() {
    return {
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
      ds: "",
      updated: 0,
    };
  },
  computed: {
    selIitems() {
      // https://v3.vuejs.org/guide/computed.html#computed-properties
      const provId = this.store.state.selection.providerId
      const m = []
      this.markers.forEach(marker => { 
        //console.log(marker)
        //if ((this.filter == 0) || (item.category_id == this.filter)) 
        if ((provId == 0) || (marker.id == provId)) 
          m.push(marker)
        })
      return m
    },
    selCenter() {
      // https://v3.vuejs.org/guide/computed.html#computed-properties
      const provId = this.store.state.selection.providerId
      const center = [49.004,  8.403]
      if (provId == 0) {
        return center
      } else {
        console.log("Search provider: ",provId)
        const marker = this.markers.find(m => m.id == provId) || 0
        console.log("Found: ",marker)
        return marker != 0 ? marker.latlng : center
      }
    },
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
    async initialize() {
      const storedProviders = await this.ds.getItem("provider") || "[]"
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

    this.ds = await DataStorage.getInstance()
 
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
