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
          label="Address"
          v-model="form.address"
          :error="v$.form.address.$error"
          error-message="Address is required."
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
        address: "",
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
          address: { required },
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
  methods: {
    async handleSubmit() {
      const isValid = await this.v$.$validate();
      if (!isValid) return;
      
      const formData = { ...this.form };
      if (this.currentStep === 1) {
        await axios.post("/sign-up/step1", formData);
        this.currentStep++;
      } else if (this.currentStep === 2) {
        await axios.post("/sign-up/step2", formData);
        this.currentStep++;
      } else if (this.currentStep === 3) {
        await axios.post("/sign-up/step3", formData);
        alert("Registration complete!");
      }
    },
  },
  setup() {
    const v$ = useVuelidate();
    return { v$ };
  },
};
</script>
