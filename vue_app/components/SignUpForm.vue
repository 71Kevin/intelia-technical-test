<template>
  <div class="container">
    <h1>Sign Up Form</h1>
    
    <form @submit.prevent="handleSubmit">
      <div v-if="currentStep === 1">
        <div class="form-group">
          <label for="fullname">Full Name</label>
          <input
          type="text"
          class="form-control"
          v-model="form.fullname"
          :class="{ 'is-invalid': v$.form.fullname.$error }"
          />
          <div v-if="v$.form.fullname.$error" class="invalid-feedback">
            Full Name is required.
          </div>
        </div>
        
        <div class="form-group">
          <label for="birthdate">Date of Birth</label>
          <input
          type="date"
          class="form-control"
          v-model="form.birthdate"
          :class="{ 'is-invalid': v$.form.birthdate.$error }"
          />
          <div v-if="v$.form.birthdate.$error" class="invalid-feedback">
            Date of Birth is required.
          </div>
        </div>
      </div>
      
      <div v-if="currentStep === 2">
        <div class="form-group">
          <label for="address">Address</label>
          <input
          type="text"
          class="form-control"
          v-model="form.address"
          :class="{ 'is-invalid': v$.form.address.$error }"
          />
          <div v-if="v$.form.address.$error" class="invalid-feedback">
            Address is required.
          </div>
        </div>
      </div>
      
      <div v-if="currentStep === 3">
        <div class="form-group">
          <label for="phone">Phone</label>
          <input
          type="text"
          v-mask="'(##) ####-####'"
          class="form-control"
          v-model="form.phone"
          :class="{ 'is-invalid': v$.form.phone.$error }"
          />
          
          <div v-if="v$.form.phone.$error" class="invalid-feedback">
            Phone is required.
          </div>
        </div>
        
        <div class="form-group">
          <label for="mobile">Mobile</label>
          <input
          type="text"
          v-mask="'(##) #####-####'"
          class="form-control"
          v-model="form.mobile"
          :class="{ 'is-invalid': v$.form.mobile.$error }"
          />
          <div v-if="v$.form.mobile.$error" class="invalid-feedback">
            Mobile is required.
          </div>
        </div>
      </div>
      
      <button type="button" @click="handleSubmit" class="btn btn-primary">
        {{ buttonLabel }}
      </button>
    </form>
  </div>
</template>

<script>
import { required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import VueMask from "v-mask";

export default {
  name: "SignUpForm",
  data() {
    return {
      currentStep: 1,
      form: {
        fullname: "",
        birthdate: "",
        address: "",
        phone: "",
        mobile: ""
      }
    };
  },
  validations() {
    if (this.currentStep === 1) {
      return {
        form: {
          fullname: { required },
          birthdate: { required }
        }
      };
    } else if (this.currentStep === 2) {
      return {
        form: {
          address: { required }
        }
      };
    } else if (this.currentStep === 3) {
      return {
        form: {
          phone: { required },
          mobile: { required }
        }
      };
    }
    return {};
  },
  computed: {
    buttonLabel() {
      return this.currentStep < 3 ? "Next" : "Submit";
    }
  },
  methods: {
    async handleSubmit() {
      console.log("Form submit triggered");
      
      try {
        console.log("Validating form...");
        
        const isValid = await this.v$.$validate();
        
        console.log("Form validation result:", isValid);
        
        if (!isValid) {
          console.log("Validation failed.");
          return;
        }
        
        const formData = { ...this.form };
        
        if (this.currentStep === 1) {
          console.log("Sending data for step 1:", formData);
          const response = await axios.post("/sign-up/step1", formData);
          console.log("Step 1 response:", response);
          this.currentStep++;
        } else if (this.currentStep === 2) {
          console.log("Sending data for step 2:", formData);
          const response = await axios.post("/sign-up/step2", formData);
          console.log("Step 2 response:", response);
          this.currentStep++;
        } else if (this.currentStep === 3) {
          console.log("Sending data for step 3:", formData);
          const response = await axios.post("/sign-up/step3", formData);
          console.log("Step 3 response:", response);
          alert("Registration complete!");
        }
      } catch (error) {
        console.error("Submission error:", error);
        alert("Error submitting data.");
      }
    }
  },
  setup() {
    const v$ = useVuelidate();
    return { v$ };
  }
};
</script>

<style scoped>
.container {
  padding-top: 20px;
  max-width: 600px;
  margin: 0 auto;
}
.is-invalid {
  border-color: red;
}
.invalid-feedback {
  color: red;
}
</style>
