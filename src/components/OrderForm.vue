
<template>

  <ion-card>
  <ion-card-header>
    <ion-card-title>Login Form</ion-card-title>
    <ion-card-subtitle>Anmeldung nur für Buchung nötig, Stöbern geht auch ohne ...</ion-card-subtitle>
  </ion-card-header>
  <ion-card-content>
  <form id="signup-form"
    class="login-form"
  >
    <h2 class="form-title">Register</h2>
    <p>
      You can place any elements you want inside a form. The inputs themselves
      can even be deeply nested.
    </p>
    <ion-item>
    <ion-label position="stacked">Stacked Label</ion-label>
    <ion-input v-model="formValues.name" class="formulate-input formulate-input-element"
      name="name"
      type="text"
      label="Your name"
      placeholder="Your name"
      validation="required"
    />
  </ion-item>

    <ion-item>
    <ion-label position="fixed">OK</ion-label>
    <ion-checkbox 
        slot="start"
        @update:modelValue="formValues.checked = $event"
        :modelValue="formValues.checked">
      </ion-checkbox>    
    <ion-input  class="formulate-input formulate-input-element"
      type="submit"
      label="Register"
      value="Register"
    />
  </ion-item>
    <pre
      class="code"
      v-text="formValues"
    />

  </form>
  </ion-card-content>
  </ion-card>

</template>

<script lang="js">
// inspired by https://vueformulate.com/guide/forms/

import { IonItem, IonLabel, IonInput, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, IonCheckbox } from '@ionic/vue';
import { defineComponent } from 'vue'; 

import DataFetch from "../services/fetch";

export default defineComponent ({
  name: "OrderForm",
  components: {
    IonItem, IonLabel, IonInput, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle ,IonCheckbox
  },
  data () {
    return {
      formValues: {},
      checked:0,
      df:"",
    }
  },
  methods:{
    async processSignupForm(e) {
      e.preventDefault();
      console.log("Signup. Checked is ",this.formValues.checked)
      const posting = {request:1,payload:this.formValues}
      const result = await this.df.post(posting)
      console.log("Post result: ",result)
      // form processing here
    }
  },
  async beforeMount(){
    this.df = await DataFetch.getInstance()
  },
  mounted() {
    const signupForm = document.getElementById('signup-form');
    signupForm.addEventListener('submit', this.processSignupForm);
  },
})

</script>

<style scoped>
.login-form {
  padding: 2em;
  border: 1px solid #a8a8a8;
  border-radius: .5em;
  max-width: 500px;
  box-sizing: border-box;
}
.form-title {
  margin-top: 0;
}

/*.login-form::v-deep .formulate-input .formulate-input-element {*/
:deep(<.login-form>) .formulate-input .formulate-input-element {
  max-width: none;
}

@media (min-width: 420px) {
  .double-wide {
    display: flex;
  }
  .double-wide .formulate-input {
    flex-grow: 1;
    width: calc(50% - .5em);
  }
  .double-wide .formulate-input:first-child {
    margin-right: .5em;
  }
  .double-wide .formulate-input:last-child {
    margin-left: .5em;
  }
}
</style>


