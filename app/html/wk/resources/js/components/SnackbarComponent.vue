<template>
  <v-snackbar
    v-model="innerSnackbar"
    :timeout="timeout"
    :multi-line="multiLine"
  >
    {{ innerMessage }}
    <slot name="link" />
    <slot v-slot:action="{ attrs }">
      <v-btn color="blue" text v-bind="attrs" @click="innerSnackbar = false">
        Close
      </v-btn>
    </slot>
  </v-snackbar>
</template>

<script>
export default {
  props: {
    snackbar: { type: Boolean },
    message: { type: String },
    multiLine: { type: Boolean, default: false },
  },
  data: () => ({
    innerSnackbar: false,
    innerMessage: null,
    timeout: 5000,
  }),
  watch: {
    snackbar: {
      immediate: true,
      handler(value) {
        this.innerSnackbar = value;
      },
    },
    message: {
      immediate: true,
      handler(value) {
        this.innerMessage = value;
      },
    },
  },
};
</script>
