<template>
  <v-snackbar
    v-model="innerSnackbar"
    :timeout="timeout"
    :multi-line="multiLine"
    :color="color"
    elevation="12"
  >
    {{ innerMessage }}
    <slot name="link" />
  </v-snackbar>
</template>

<script>
export default {
  props: {
    snackbar: { type: Boolean },
    message: { type: String, default: "" },
    multiLine: { type: Boolean, default: false },
    timeout: { type: Number, default: 5000 },
    color: { type: String, default: "success" },
  },
  data: function () {
    return {
      innerSnackbar: false,
      innerMessage: null,
    };
  },
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
    innerSnackbar: function (after, before) {
      this.$emit("snackbarChanged", after);
    },
  },
};
</script>
