<template>
  <v-snackbar
    v-model="innerSnackbar"
    :timeout="timeout"
    :multi-line="multiLine"
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
  },
  data: function () {
    return {
      innerSnackbar: false,
      innerMessage: null,
      timeout: 5000,
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
  },
};
</script>
