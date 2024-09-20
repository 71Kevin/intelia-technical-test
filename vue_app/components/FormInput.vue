<template>
  <div class="form-group">
    <label :for="id">{{ label }}</label>
    <input
    :id="id"
    :type="type"
    class="form-control"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    :class="{ 'is-invalid': error }"
    />
    <div v-if="error" class="invalid-feedback">{{ errorMessage }}</div>
  </div>
</template>

<script>
export default {
  name: "FormInput",
  props: {
    modelValue: String,
    label: String,
    type: { type: String, default: "text" },
    error: Boolean,
    errorMessage: String
  },
  computed: {
    id() {
      return `input-${this.label.toLowerCase().replace(/\s+/g, "-")}`;
    }
  }
};
</script>
