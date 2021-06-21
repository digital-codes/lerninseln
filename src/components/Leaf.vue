<template>
  <div id="mapId">

  <l-map style="height:70vh"
    :zoom="zoom"
    :center="center"
    :max-zoom="maxZoom"
    @click="addPoint"
    @update:center="centerUpdate"
    @update:zoom="zoomUpdate"
  >

 <l-tile-layer 
        :url="url" 
        :attribution="attribution"
  >
  </l-tile-layer>

  <l-marker v-for="item in markers" :key="item.id" :lat-lng="item.latlng"
      @l-add="$event.target.openPopup()"
  >
      <l-popup :content="item.content">
      </l-popup>
  </l-marker>


  
  </l-map>
  
  </div>
</template>

<script>

// leaf2vue docs: https://vue2-leaflet.netlify.app/examples/feature-group.html

// DON'T load Leaflet components here!
// Its CSS is needed though, if not imported elsewhere in your application.
import "leaflet/dist/leaflet.css"
import { LMap, LGeoJson,LMarker, LPopup, LTileLayer } from "@vue-leaflet/vue-leaflet";

//let startPnt = [49.004,  8.403]

export default {
  name: "Leaf",
  components: {
    LMap,
    LTileLayer,
    LPopup,
    LMarker,
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
    };
  },
  methods : {
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
      this.markers.push({"id":this.geokey,"latlng":this.startPnt,"content":content})
      this.geokey += 1
      //console.log(this.markers)
    },
    initialize() {
      for (let i=0;i<5;i++) {
        const pnt =  [this.startPnt[0] += .0005 * i, this.startPnt[1] += .0005]
        const content = '<div class="popInfo">234<br>Click for more<p><a href="https://cern.ch" target="_blank">Link</a></p></div>'
        this.markers.push({"id":this.geokey,"latlng":pnt,"content":content})
        this.geokey += 1
      }
    },
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

    this.initialize();

  },
};
</script>

<style>
  .popInfo {
    font-weight: bold;
  }
</style>