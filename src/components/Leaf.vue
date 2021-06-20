<template>
  <div id="mapId">

  <l-map style="height:70vh"
    :zoom="zoom"
    :center="center"
    :maxZoom="maxZoom"
    @click="addPoint"
    @update:center="centerUpdate"
    @update:zoom="zoomUpdate"
  >
  <l-geo-json
    v-model="geojson" 
    :geojson="geojson" 
    :options="geojsonOptions" />

 <l-tile-layer 
        :url="url" 
        :attribution="attribution"
  >
  </l-tile-layer>
  <l-marker :lat-lng="withPopup">
        <l-popup>
          <div @click="innerClick">
            I am a popup
            <p v-show="showParagraph">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
              sed pretium nisl, ut sagittis sapien. Sed vel sollicitudin nisi.
              Donec finibus semper metus id malesuada.
            </p>
          </div>
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
    LGeoJson,
    LTileLayer
  },
  data() {
    return {
      zoom:13,
      maxZoom:17,
      //center1: latlng(49.004,  8.403),
      //center2: geojsonOptions.latlng(49.004,  8.403),
      //center: this.geojsonOptions.latlng(49.004,  8.403),
      center: [49.004,  8.403],
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution:
        '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      withPopup: [49.004,  8.403],
      geojson: {
        type: "FeatureCollection",
        "features": [{
            "type": "Feature",
            "geometry": {
              "type": "Point",
              "coordinates": [8.400170783538151, 49.00709328041582],
            },
            "properties": {
              "NAME": "Staatliches Museum für Naturkunde",
              "GRUPPENNAME_DE": "Museen, Ausstellungen",
              "UPDATED": 1538352000000
            }
          },
          {
            "type": "Feature",
            "geometry": {
              "type": "Point",
              "coordinates": [8.383544112981063, 49.00070066892714]
            },
            "properties": {
              "NAME": "Kultur-Institut Kunstsammlungen/Städtische Galerie",
              "GRUPPENNAME_DE": "Museen, Ausstellungen",
              "UPDATED": 1538352000000
            }
          }
        ]
      },
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
    const features = this.geojson.features;
    this.startPnt[0] += .0001
    this.startPnt[1] += .0001
    console.log("Start:", this.startPnt)
    const pnt = {
          "type": "Feature",
          "geometry": {
            "type": "Point",
            "coordinates": this.startPnt,
          },
          "properties": {
            "NAME": "xyz",
          }
        }
      features.push(pnt)
      console.log(features)
    }
  },
  async beforeMount() {
    // HERE is where to load Leaflet components!
    const { map, marker, tileLayer, markerLayer, LayerGroup, latLng } = await import("leaflet/dist/leaflet-src.esm");
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
 
    // points
    //const mark = new LMarker([kaLat.center,kaLon.center]);
    //const pop = new LPopup("<b><center>Hier bist du</center></b>");
    //pop.bind(mark)
    //mark.addTo(LMap);
    /*    
    theMarker.bindPopup("<b><center>Hier bist du</center></b>");
    theMarker.openPopup();
    theMarker.addTo(poiLayer);
    //pm.bindPopup("<b>" + kaPois.info[p].name + "</b><br>" + kaPois.info[p].zone + story)
    //"<img src=\"" + kaPois.info[p].thumb + "\">" + story)
    //theMap.addLayer(poiLayer);
    poiLayer.addTo(theMap);
    //poiLayer.addTo(theMap);
    */
    /*
    // streets
    const streets = tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      //maxBounds: bounds,
      attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
      //id: 'mapbox.streets'
    })
    streets.addTo(theMap);
    //streets.addTo(poiLayer);
    */
    /*
    const streets = new tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      //maxBounds: bounds,
      attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
      //id: 'mapbox.streets'
    })

    streets.addTo(LTileLayer)
    */
    //LMap.setView([kaLat.center, kaLon.center]);

    //theMap.setZoom(12);


    // And now the Leaflet circleMarker function can be used by the options:
    //this.geojsonOptions.pointToLayer = (feature, latLng) =>
     // circleMarker(latLng, { radius: 8 });
    //this.geojsonOptions.latlng = (lat, lon) => latLng(lat,lon);

    this.startPnt = [49.004,  8.403]
    this.mapIsReady = true;
  },
};
</script>

