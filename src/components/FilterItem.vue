<template>
    <ion-item-group class="filter">
    <!--
    <ion-item class="filterItem"  lines="none">
          <ion-label class="filterLabel">{{name}}</ion-label>
    </ion-item>
    -->

    <!--
        @ionChange="toppings.value.push($event.target.value)"
        value="pepperoni"
        :checked="toppings.value.indexOf('pepperoni') !== -1">

... from check box
          @update:modelValue="checked = $event"
            :modelValue="check"

        .. works almos but toggles too much...
          :checked=check
          
          @ionChange="filter()" 

    -->

    <ion-item lines="none">
            <ion-icon :icon=icon class="filterIcon"/>
    </ion-item>
    <ion-item class="filterToggle" lines="none">
          <ion-checkbox
          @update:modelValue="{checked = $event;filter()}"
          :modelValue="checked"
          >
          </ion-checkbox>
    </ion-item>
    
    </ion-item-group>
</template>

<script lang="ts">
import {IonCheckbox, IonIcon, IonItemGroup, IonItem } from '@ionic/vue';
import { defineComponent } from 'vue';



export default defineComponent ({
  name: "FilterItem",
  components: { IonCheckbox, IonIcon, IonItemGroup, IonItem
  },
  data () {
    return {
      checked: this.check
    }
  },
  props: {
    "name": String,
    "icon": IonIcon,
    "check": Boolean
  },
  emits: ['filter'],
  methods: {
    filter(){
      console.log("F1:", this.name," - ",this.check, this.checked)
      if (this.checked) {
        this.$emit("filter")
      }
    },
  },

  watch: {
    "check": function (a,b) { 
        console.log(this.name," : ",a,b)
        this.checked = a
    }
  }

});
</script>

<style scoped>

.hdr {
  color: unset;  
}

.center {
  text-align: center;

}

.label {

}

.filter {
  display: inline-block;
  max-width:18%;
}

.filterLabel {
  text-align: center;

}
.filterIcon {
  text-align: center;
  margin-left:auto;
  margin-right:auto;
  width:100;
}

.filterToggle {
  text-align: center;
}

ion-toggle {
  width: 40px;
   height: 16px;
   --handle-width: 16px;
   --handle-height: 20px;
  --handle-spacing: 0px;
  --border-radius: 12px;
  --handle-border-radius: 12px;
}

ion-item {
  --padding-start: 5px;
  --inner-padding-end: 5px;
}

</style>
