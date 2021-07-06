
<template>

  <form ref="orderForm" v-show="showSubscription == true"
    class="login-form"
  >
    <p>Zur Buchung benötigen wir Deine Email-Adresse. Wir senden Dir dann einen Code,
    den Du hier eingibst. Du bekommst danach den QR-Code und ein
    Ticket zum Ausdrucken.
    </p>
    <p class="footnote">Bitte markiere mit "OK", dass Du der Verarbeitung
    Deiner Daten zustimmst.
    </p> 
    <ion-item>
    <ion-label class="input-label" position="stacked">Email</ion-label>
    <ion-input v-model="formValues.email" class="input-item"
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

  <form ref="confirmationForm" v-show="showSubscription == false"
    class="login-form"
  >
    Bitte gib den Code ein, den wir Dir an Deine Email Adressen gesendet haben.
    <ion-item>
    <ion-label class="input-label" position="stacked">Code</ion-label>
    <ion-input v-model="formValues.code" class="input-item"
      email="code"
      type="string"
      minlength=6
      maxlength=6
      placeholder="Code"
      validation="required"
    />
  </ion-item>

    <ion-item>
    <ion-button  class="submit-button" ref="confirmButton"
      type="submit"
      label="Register"
      value="Register"
    >Senden</ion-button>
  </ion-item>
  </form>

  <ion-text v-if="message > ''" class="message"><p>{{message}}</p></ion-text>

  <ion-text v-if="info > ''" class="info"><p>{{info}}</p></ion-text>

</template>

<script lang="js">
// inspired by https://vueformulate.com/guide/forms/

import { IonItem, IonLabel, IonInput, IonText, IonButton, IonCheckbox } from '@ionic/vue';
import { defineComponent } from 'vue'; 

import DataFetch from "../services/fetch";

import { useStore, Selection, MUTATIONS, ACTIONS } from '../services/quickStore';


export default defineComponent ({
  name: "OrderForm",
  components: {
    IonItem, IonLabel, IonInput, 
    //IonCardSubtitle, IonCardTitle ,
    IonCheckbox, IonButton
  },
  props: {
    info: {type: String, default: "",},
  },
  emits: ["purchaseComplete"],  // vue3 requires to define events here!
  data () {
    return {
      formValues: {checked:0},
      df:"",
      payload: {},
      showSubscription: true,
      message: "",
    }
  },
  methods:{
    async processSignupForm(e) {
      e.preventDefault();
      if (!this.formValues.checked) {
        console.log("Not checked")
        return
      }
      const email = this.formValues.email
      const purchase = this.store.state.purchase
      purchase.email = email
      this.store.commit(MUTATIONS.SET_PURCHASE,purchase)
      // DON'T: this.store.state.purchase.email = email
      console.log("Signup. Checked is ",this.formValues.checked)
      const posting = {request:1,payload:{
        ticket: this.store.state.purchase.ticket, 
        email: this.store.state.purchase.email,
        }
      }
      const result = await this.df.post(posting)
      console.log("Post1 result: ",result)
      this.payload = result.payload
      // check axios status
      if (!result.status) {
        console.log("Error1:", result.code," ",this.payload)
        return
      }
      console.log("OK1",this.payload)
      this.message = result.payload.text
      // check response status
      if (!this.payload.status) {
        console.log("Response Error1:", this.payload.text)
        return
      }
      console.log("OK2",this.payload)
      // we might receive status 2 if already reserved. message will be displayed
      if (this.payload.status == 2) {
        console.log("Already pending", this.payload.text)
      }
      // open confirmation form
      this.formValues.code = ""
      this.showSubscription = false
    },
    async processConfirmationForm(e) {
      e.preventDefault();
      const code = this.formValues.code
      if (String(code).length != 6)
        return
      const posting = {request:2,payload:{
        ticket: this.store.state.purchase.ticket, 
        email: this.store.state.purchase.email,
        code: code
        }
      }
      const result = await this.df.post(posting)
      console.log("Post2 result: ",result)
      this.payload = result.payload
      // check axios status
      if (!result.status) {
        console.log("Error2:", result.code," ",result.payload)
        return
      }
      console.log("OK2")
      this.message = result.payload.text
      // check response status
      if (!this.payload.status) {
        console.log("Response Error1:", this.payload.text)
        return
      }
      //console.log("OK2",this.payload)

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
    const confirmationForm = this.$refs.confirmationForm
    confirmationForm.addEventListener('submit', this.processConfirmationForm);
  },
  // store
  setup() {
    const store = useStore();
    return { store};
  },
})

</script>

<style scoped>
.login-form {
  padding: 0;
  border: 0; /* 1px solid #a8a8a8; */
  border-radius: .5em;
  box-sizing: border-box;
  margin-top: 1em;
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
  /* font-size: 90%; */
  font-style: italic;
}

.info {
  font-size: 120%;
  color: rgb(200,0,0);
}

.message {
  font-size: 120%;
  color: rgb(0,0,250);
}

.input-item {
  
}

.input-label {
  
}

.submit-button {

}

</style>


