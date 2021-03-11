<template>
  <v-card flat class="mb-12">
    <v-file-input
      counter
      v-model="fileInfo"
      show-size
      accept="application/pdf"
      :error="errors.uploadFile"
      :error-messages="messages.uploadFile"
    ></v-file-input>
  </v-card>
</template>

<script>
export default {
  data: function () {
    return {
      fileInfo: null,
      errors: {
        uploadFile: false,
      },
      messages: {
        uploadFile: null,
      },
    };
  },
  methods: {
    fileUpload() {
      const formData = new FormData();
      formData.append("uploadFile", this.fileInfo);

      this.overlay = true;
      axios
        .post("/api/pdfsort/fileupload", formData, { withCredentials: true })
        .then((response) => {
          this.snackbar = true;
          this.snackbarMessage = `アップロードに成功しました。: ${response.data.name}`;

          console.log(response);
          this.fileInfo = null;
          this.step = 2;
        })
        .catch((error) => {
          let res = error.response;
          console.log(res.data.errors);

          if (res.status == 422) {
            Object.keys(res.data.errors).forEach((key) => {
              this.errors[key] = true;
              this.messages[key] = res.data.errors[key];
            });
          } else {
            console.log(res);

            this.snackbar = true;
            this.snackbarColor = "error";
            this.snackbarMessage = "システムエラーが発生しました。";
          }
        })
        .finally(() => {
          this.overlay = false;
        });
    },
  },
};
</script>
