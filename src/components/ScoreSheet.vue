<template>

  <h3>Score Sheet</h3>
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

export default defineComponent ({
  name: "ScoreSheet",
  components: {
      apexchart: VueApexCharts,
  },
  data () {
    return {
      series: [
        {
          name: 'sales',
          data: [3,4,5,5,7,7,9,11,23],
        },
        {
          name: 'sales1',
          data: [40,35,50,49,60,70,91,125,200],
        },
      ],
      type: "bar",
      options : {
        xaxis: {
          categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
        },
        yaxis: [
        {
          opposite: true,
          axisTicks: {
            show: true,
          },
          axisBorder: {
            show: true,
            color: '#ff0000'
          },
          labels: {
            style: {
              colors: '#000000',
            }
          },
          title: {
            text: "Right side",
            style: {
              color: '#ff0000',
            }
          },
          tooltip: {
            enabled: true
          }
        },
        {
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
            }
          },
          title: {
            text: "Left side",
            style: {
              color: '#00ff00',
            }
          },
          tooltip: {
            enabled: true
          }
        },
        ],
        // https://apexcharts.com/docs/colors/
        colors: ['#ff0000', '#00ff00'], // global for series
        /* 
        fill: {
          colors: ['#ff0000', '#00ff00'] // bars, lines ...
        },
        */
        dataLabels: {
          style: {
            colors: ['#000000', '#000000',]
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
                  horizontal: true
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
