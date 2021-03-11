<template>
  <div>
    <h3>pdf並び変え</h3>
    <v-stepper v-model="step" alt-labels>
      <v-stepper-header>
        <v-stepper-step :complete="step > 1" step="1">
          pdfアップロード
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step :complete="step > 2" step="2">
          ソートキー選択
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step step="3"> ソート結果 </v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content step="1">
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

          <v-btn color="primary" @click="fileUpload"> Next </v-btn>
        </v-stepper-content>

        <v-stepper-content step="2">
          <v-card flat class="mb-12">
            <pdf-sort-key-select-component
              :id="rekognitionResourcesId"
              @change-sort-key="changeSortKey"
            ></pdf-sort-key-select-component>
          </v-card>

          <v-btn color="primary" @click="sortPdf"> Next </v-btn>

          <v-btn text @click="step = 1"> Prev </v-btn>
        </v-stepper-content>

        <v-stepper-content step="3">
          <v-card flat class="mb-12">
            <a
              :href="sortedPdfPath"
              target="_blank"
              rel="noopener noreferrer"
            >ファイルがソートされました。</a>
          </v-card>

          <v-btn text @click="step = 2"> Prev </v-btn>
        </v-stepper-content>
      </v-stepper-items>

      <snackbar-component
        :snackbar="snackbar"
        :message="snackbarMessage"
        :multi-line="true"
        :color="snackbarColor"
      >
      </snackbar-component>
      <v-overlay :value="overlay">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
    </v-stepper>
  </div>
</template>

<script>
import PdfSortKeySelectComponent from "./PdfSortKeySelectComponent.vue";
import PdfSortUploadComponent from "./PdfSortUploadComponent.vue";

export default {
  components: {
    "pdf-sort-key-select-component": PdfSortKeySelectComponent,
    "pdf-sort-upload-component": PdfSortUploadComponent,
  },
  data: function () {
    return {
      step: 1,
      fileInfo: null,
      errors: {
        uploadFile: false,
      },
      messages: {
        uploadFile: null,
      },
      snackbar: false,
      snackbarMessage: null,
      snackbarColor: null,
      snackbarlink: "",
      overlay: false,
      rekognitionResourcesId: null,
      selectedSortKey: null,
      sortedPdfPath: null,
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
          this.snackbarMessage = `アップロードに成功しました。: ${response.data.resource_original_name}`;
          this.rekognitionResourcesId = response.data.id;

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
    sortPdf: function () {
      const formData = new FormData();
      formData.append("id", this.rekognitionResourcesId);
      formData.append("sort_id", this.selectedSortKey);

      this.overlay = true;
      axios
        .post("/api/pdfsort/sort", formData, { withCredentials: true })
        .then((response) => {
          this.snackbar = true;
          this.snackbarMessage = `ソート中です`;

          console.log(response);
          this.step = 3;
        })
        .catch((error) => {
          let res = error.response;
          console.error(res);

          this.snackbar = true;
          this.snackbarColor = "error";
          this.snackbarMessage = "システムエラーが発生しました。";
          this.overlay = false;
        });
    },
    changeSortKey: function (id) {
      this.selectedSortKey = id;
    },
  },
};
</script>
