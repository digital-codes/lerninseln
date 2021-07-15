<template>


  <!--
  <apexchart width="500" :type="type" :options="options" :series="series"></apexchart>
  -->
  <apexchart  width="100%"  :type="type" :options="options" :series="series"></apexchart>

</template>

<script lang="ts">

import { defineComponent } from 'vue';

// https://apexcharts.com/docs/vue-charts/
// local import working, see axample at https://apexcharts.com/vue-chart-demos/bar-charts/grouped/
// https://apexcharts.com/javascript-chart-demos/mixed-charts/multiple-yaxis/

import VueApexCharts from "vue3-apexcharts";

const ranks = ["Neuling","Mittelfeld","Top 10","Number One"]

export default defineComponent ({
  name: "ScoreSheet",
  components: {
      apexchart: VueApexCharts,
  },
  data () {
    return {
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
        {
          name: 'Dein Status',
          data: [2],
        },
      ],
      type: "bar",
      options : {
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
        {
          opposite: true,
          tickAmount: 4,
          //min: 3,
          max: 4,
          axisTicks: {
            show: false,
          },
          axisBorder: {
            show: true,
            color: '#ff0000'
          },
          labels: {
            formatter: (value: any) => { return ranks[value - 1] },
            style: {
              colors: '#000000',
              fontSize: '1em',
              fontFamily: 'sans-serif',
              fontWeight: 400,
            }
          },
          title: {
            text: "Dein Status",
            rotate: 90,
            style: {
              color: '#ff0000',
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
            height: "400px",
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
            height: "300px",
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


</style>
