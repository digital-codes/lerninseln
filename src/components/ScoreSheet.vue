<template>

  <!--
  <apexchart width="500" :type="type" :options="options" :series="series"></apexchart>
  -->
  <apexchart width="100%" :type="type" :options="options" :series="series"></apexchart>
</template>

<script lang="ts">

import { defineComponent } from 'vue';

// https://apexcharts.com/docs/vue-charts/
// local import not working. maybe check vue documentation

export default defineComponent ({
  name: "ScoreSheet",
  data () {
    return {
      series: [
        {
          name: 'sales',
          data: [30,40,35,50,49,60,70,91,125]
        },
        {
          name: 'sales1',
          data: [40,35,50,49,60,70,91,125,200]
        },
      ],
      type: "bar",
      options : {
        xaxis: {
          categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
        },
        responsive: [
          {
            breakpoint: 600,
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
