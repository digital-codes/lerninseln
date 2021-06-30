
<template>

  <ion-card>
  <ion-card-content>
  <form ref="orderForm"
    class="login-form"
  >
    Zur Buchung benötigen wir Deine Email-Adresse. Wir senden Dir dann einen Code,
    den Du hier eingibst. Du bekommst danach den QR-Code und ein
    Ticket zum Ausdrucken.
    <p class="footnote">Bitte markiere mit "OK", dass Du der Verarbeitung
    Deiner Daten zustimmst.
    </p> 
    <ion-item>
    <ion-label class="input-label" position="stacked">Email</ion-label>
    <ion-input v-model="formValues.name" class="input-item"
      email="email"
      type="email"
      placeholder="Email"
      validation="required"
    />
  </ion-item>

    <ion-item>
    <ion-label class="check-label" position="fixed">OK</ion-label>
    <ion-checkbox class="check-box"
        slot="start"
        @update:modelValue="formValues.checked = $event"
        :modelValue="formValues.checked">
      </ion-checkbox>    
    <ion-button  class="submit-button"
      type="submit"
      label="Register"
      value="Register"
    >Senden</ion-button>
  </ion-item>
    <!--
    <pre
      class="code"
      v-text="formValues"
    />
    <pre
      class="code"
      v-text="payload"
    />
    -->
  </form>
  </ion-card-content>
  </ion-card>

</template>

<script lang="js">
// inspired by https://vueformulate.com/guide/forms/

import { IonItem, IonLabel, IonInput, IonButton, IonCard, IonCardContent, IonCardSubtitle, IonCardTitle, IonCheckbox } from '@ionic/vue';
import { defineComponent } from 'vue'; 

import DataFetch from "../services/fetch";

export default defineComponent ({
  name: "OrderForm",
  components: {
    IonItem, IonLabel, IonInput, IonCard, IonCardContent, 
    //IonCardSubtitle, IonCardTitle ,
    IonCheckbox, IonButton
  },
  emits: ["purchaseComplete"],  // vue3 requires to define events here!
  data () {
    return {
      formValues: {checked:0},
      df:"",
      payload: {}
    }
  },
  methods:{
    async processSignupForm(e) {
      e.preventDefault();
      console.log("Signup. Checked is ",this.formValues.checked)
      const posting = {request:1,payload:this.formValues}
      const result = await this.df.post(posting)
      console.log("Post result: ",result)
      this.payload = result.payload
      if (result.status) {
        console.log("OK")
      } else {
        console.log("Error:", result.code," ",result.payload)
      }
      this.payload.status = 1 // after validation
      this.$emit("purchaseComplete",this.payload)
      // form processing here
    }
  },
  async beforeMount(){
    this.df = await DataFetch.getInstance()
  },
  mounted() {
    //const signupForm = document.getElementById('signup-form');
    // use vue refs method to access the form instance
    const signupForm = this.$refs.orderForm
    signupForm.addEventListener('submit', this.processSignupForm);
  },
})

</script>

<style scoped>
.login-form {
  padding: 1em;
  border: 1px solid #a8a8a8;
  border-radius: .5em;
  box-sizing: border-box;
}
.form-title {
  margin-top: 0;
}

.check-box {
  margin-inline-end: 10px;
}

.check-label {
  max-width: 50px;
  min-width: 30px;
  flex: 0 0 20px;
}

.footnote {
  font-size: 80%;
}
.input-item {
  
}

.input-label {
  
}

.submit-button {

}

</style>


