<template>
  <div class="form-wrapper">
    <div class="form-container">
      <h1>Sign Up Form</h1>
      
      <form @submit.prevent="handleSubmit">
        <div v-if="currentStep === 1">
          <FormInput
          label="Full Name"
          v-model="form.fullname"
          :error="v$.form.fullname.$error"
          error-message="Full Name is required."
          />
          <FormInput
          label="Date of Birth"
          type="date"
          v-model="form.birthdate"
          :error="v$.form.birthdate.$error"
          error-message="Date of Birth is required."
          />
        </div>
        
        <div v-if="currentStep === 2">
          <FormInput
          label="Street"
          v-model="form.street"
          :error="v$.form.street.$error"
          error-message="Street is required."
          />
          <FormInput
          label="Number"
          v-model="form.number"
          :error="v$.form.number.$error"
          error-message="Number is required."
          />
          <FormInput
          label="Zip Code"
          v-mask="'#####-###'"
          v-model="form.zipCode"
          :error="v$.form.zipCode.$error"
          error-message="Zip Code is required."
          />
          <FormInput
          label="City"
          v-model="form.city"
          :error="v$.form.city.$error"
          error-message="City is required."
          />
          <FormInput
          label="State"
          v-model="form.state"
          :error="v$.form.state.$error"
          error-message="State is required."
          />
        </div>
        
        <div v-if="currentStep === 3">
          <FormInput
          label="Phone"
          v-mask="'(##) ####-####'"
          v-model="form.phone"
          :error="v$.form.phone.$error"
          error-message="Phone is required."
          />
          <FormInput
          label="Mobile"
          v-mask="'(##) #####-####'"
          v-model="form.mobile"
          :error="v$.form.mobile.$error"
          error-message="Mobile is required."
          />
        </div>
        
        <div class="form-actions">
          <button type="button" @click="handleSubmit" class="btn btn-primary">
            {{ buttonLabel }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import FormInput from "./FormInput.vue";
import axios from "axios";
import "../assets/css/signupform.css";

export default {
  name: "SignUpForm",
  components: { FormInput },
  data() {
    return {
      currentStep: 1,
      form: {
        fullname: "",
        birthdate: "",
        street: "",
        number: "",
        zipCode: "",
        city: "",
        state: "",
        phone: "",
        mobile: "",
      },
    };
  },
  validations() {
    if (this.currentStep === 1) {
      return {
        form: {
          fullname: { required },
          birthdate: { required },
        },
      };
    } else if (this.currentStep === 2) {
      return {
        form: {
          street: { required },
          number: { required },
          zipCode: { required },
          city: { required },
          state: { required },
        },
      };
    } else if (this.currentStep === 3) {
      return {
        form: {
          phone: { required },
          mobile: { required },
        },
      };
    }
    return {};
  },
  computed: {
    buttonLabel() {
      return this.currentStep < 3 ? "Next" : "Submit";
    },
  },
  mounted() {
    this.fetchSavedData();
  },
  methods: {
    async fetchSavedData() {
      try {
        const response = await axios.get("/sign-up/fetch-saved-data");
        if (response.data && Object.keys(response.data).length > 0) {
          this.form = { ...this.form, ...response.data };
        } else {
          this.form = {
            fullname: "",
            birthdate: "",
            street: "",
            number: "",
            zipCode: "",
            city: "",
            state: "",
            phone: "",
            mobile: "",
          };
        }
      } catch (error) {
        console.error("Error fetching saved data:", error);
      }
    },
    
    async handleSubmit() {
      const isValid = await this.v$.$validate();
      if (!isValid) return;
      
      const formData = { ...this.form };
      try {
        if (this.currentStep === 1) {
          await axios.post("/sign-up/step1", formData);
          this.currentStep++;
        } else if (this.currentStep === 2) {
          await axios.post("/sign-up/step2", formData);
          this.currentStep++;
        } else if (this.currentStep === 3) {
          await axios.post("/sign-up/step3", formData);
          alert("Registration complete!");
          
          await axios.post("/sign-up/clear-session");
        }
      } catch (error) {
        console.error("Error during form submission:", error.response || error);
        alert("An error occurred: " + (error.response?.data?.message || "Unexpected error."));
      }
    }
    
  },
  setup() {
    const v$ = useVuelidate();
    return { v$ };
  },
};
</script>
