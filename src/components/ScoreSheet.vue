<template>


  <!--
  <apexchart width="500" :type="type" :options="options" :series="series"></apexchart>
  -->
  <div  class="chart">
  <apexchart  width="100%" height="100%" :type="type" :options="options" :series="series"></apexchart>
  </div>

  <h3>Dein Status:</h3>
  <div class="scoreContainer">
  <ion-chip v-for="(score,id) in scores" :key="id" :disabled="score.disabled" 
    :class="{score, pos1: id == 0, pos2: id == 1, pos3: id == 2, pos4: id == 3 }" 
    @click="scoreClicked(id)">
    <ion-img class="scoreIcon" :src="score.icon">
    </ion-img>
    <ion-label class="scoreLabel" >{{score.label}}
    </ion-label>
  </ion-chip>

 <ion-popover
    :is-open="popOpen"
    css-class="scorePop"
    :translucent="true"
    @didDismiss="popState(false)"
  >
    <Popover>{{scoreInfo}}</Popover>
  </ion-popover>

  </div>

  
</template>

<script lang="ts">

import { IonItem, IonImg, IonLabel, IonChip, IonPopover } from '@ionic/vue';

import { defineComponent } from 'vue';
import Popver from './popover.vue'

// https://apexcharts.com/docs/vue-charts/
// local import working, see axample at https://apexcharts.com/vue-chart-demos/bar-charts/grouped/
// https://apexcharts.com/javascript-chart-demos/mixed-charts/multiple-yaxis/
// https://apexcharts.com/react-chart-demos/scatter-charts/scatter-images/

import VueApexCharts from "vue3-apexcharts";

const ranks = ["Neuling","Mittelfeld","Top 10","Number One"]

export default defineComponent ({
  name: "ScoreSheet",
  components: {
      apexchart: VueApexCharts, 
      IonImg, IonLabel, IonChip,
      //IonItem, 
      IonPopover,
  },
  methods: {
    popState(v: boolean) {
      this.popOpen = v
      if (!v) {
        this.scoreInfo = ""
        console.log("Close pop")
      }
    },
    scoreClicked(i: number) {
      console.log("Clicked:",i)
      this.scoreInfo = this.scores[i].text
      this.popState(true)
    },
  },
  data () {
    return {
      popOpen: false,
      scoreInfo: "",
      scores: [
        {
          icon: "/assets/img/scores/snail.png",
          label: "Neuling",
          disabled:"true",
          text: "Du hast wohl grade erst angefangen. Mach ein paar Buchungen",
        },
        {
          icon: "/assets/img/scores/dog.png",
          label: "Mittelfeld",
          disabled:"false",
          text: "Du hast schon einiges erreicht. Weiter so",
        },
        {
          icon: "/assets/img/scores/horse.png",
          label: "Top Ten",
          disabled:"true",
          text: "Du bist in der Führungsgruppe. Schaffst Du den ersten Platz?",
        },
        {
          icon: "/assets/img/scores/unicorn.png",
          label: "Number One",
          disabled:"true",
          text: "Ganz vorne! Glückwunsch.",
        },
      ],
      //
      series: [
        {
          name: 'Angebote',
          data: [15],
        },
        {
          name: 'Buchungen',
          data: [35],
        },
        {
          name: 'Bewertungen',
          data: [30],
        },
      ],
      type: "bar",
      options : {
        chart: {
          toolbar: {
            show: false,
          },
        },
        xaxis: {
          categories: ["Juli"],
          labels: {
            style: {
              colors: '#000000',
              fontSize: '1em',
              fontFamily: 'sans-serif',
              fontWeight: 600,
            }
          },
        },
        yaxis: [
        {
          //opposite: true,
          show: false,
          axisTicks: {
            show: true,
          },
          axisBorder: {
            show: true,
            color: '#00ffff'
          },
          labels: {
            style: {
              colors: '#000000',
              fontSize: '1em',
              fontFamily: 'sans-serif',
              fontWeight: 400,
            }
          },
          title: {
            text: "Angebote",
            style: {
              color: '#00ffff',
              fontSize: '1em',
              fontFamily: 'sans-serif',
              fontWeight: 400,
            }
          },
          tooltip: {
            enabled: false
          }
        },
        {
          show: false,
          axisTicks: {
            show: true,
          },
          axisBorder: {
            show: true,
            color: '#00ff00'
          },
          labels: {
            style: {
              colors: '#000000',
              fontSize: '1em',
              fontFamily: 'sans-serif',
              fontWeight: 400,
            }
          },
          title: {
            text: "Buchungen",
            style: {
              color: '#00ff00',
              fontSize: '1em',
              fontFamily: 'sans-serif',
              fontWeight: 400,
            }
          },
          tooltip: {
            enabled: false
          }
        },
        {
          show: false,
          axisTicks: {
            show: true,
          },
          axisBorder: {
            show: true,
            color: '#0000ff'
          },
          labels: {
            //formatter: (value: any) => { return ranks[value - 1] },
            style: {
              colors: '#000000',
              fontSize: '1em',
              fontFamily: 'sans-serif',
              fontWeight: 400,
            }
          },
          title: {
            text: "Bewertungen",
            style: {
              color: '#0000ff',
              fontSize: '1em',
              fontFamily: 'sans-serif',
              fontWeight: 400,
            }
          },
          tooltip: {
            enabled: false
          }
        },
        ],
        // https://apexcharts.com/docs/colors/
        colors: ['#00ffff', '#00ff00','#0000ff',"#ff0000"], // global for series
        /* 
        fill: {
          colors: ['#ff0000', '#00ff00'] // bars, lines ...
        },
        */
        dataLabels: {
          enabledOnSeries: [0,1,2],
          style:{
            colors: ['#000000', '#000000','#000000',"#000000"],
            fontSize: "1em",
            fontFamily: 'sans-serif',
            fontWeight: 400,
          }
        },
        responsive: [
          {
            breakpoint: 600,
            //height: "400px", // height set by container
            options: {
              plotOptions: {
                bar: {
                  // horizonatl bars with multiple Y axis not possible!
                  horizontal: false
                }
              },
              legend: {
                position: "bottom"
              },
            }
          },
          {
            breakpoint: 400,
            //height: "300px",
            options: {
              plotOptions: {
                bar: {
                  horizontal: false
                }
              },
              legend: {
                position: "bottom"
              }
            }
          }
        ]
      },
    }
  },
});


</script>

<style scoped>

.score {
  height: 100px;
  text-align: center;
}

.scoreContainer {
  width:100%;
  display: grid;
}

.pos1 {
  grid-column: 1;
}
.pos2 {
  grid-column: 2;
}
.pos3 {
  grid-column: 3;
}
.pos4 {
  grid-column: 4;
}

.scoreIcon {
  width: 80px;
  height: 80px;
  padding-left: 1em;
}

.scoreLabel {
  padding-left: .5em;
}

.chart {
  height: 500px;
}

@media only screen and (max-width: 400px) {
  .chart {
    height: 300px;
  }
}

@media only screen and (max-width: 600px) {
  /*
  .score {
    display:block;
  }
.scoreLabel {
  padding-left: 0;
}
*/
.score {
  height: 60px;
}

.scoreIcon {
  width: 50px;
  height: 50px;
  padding-left: .2em;
}

.pos1 {
  grid-column: 1 / span 2;
}
.pos2 {
  grid-column: 3 / span 2;
}
.pos3 {
  grid-column: 1 / span 2;
}
.pos4 {
  grid-column: 3 / span 2;
}

}

@media only screen and (min-width: 600px) {
  .chart {
    height: 400px;
  }

}

@media only screen and (min-width: 1000px) {
  .chart {
    height: 500px;
  }
}



</style>
