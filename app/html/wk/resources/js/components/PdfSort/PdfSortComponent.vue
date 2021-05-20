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
            <v-sheet></v-sheet>
            <v-file-input
              counter
              v-model="fileInfo"
              show-size
              accept="application/pdf"
              :error="errors.uploadFile"
              :error-messages="messages.uploadFile"
            ></v-file-input>
          </v-card>

          <v-btn color="primary" @click="fileUpload"> ソートキー抽出 </v-btn>
        </v-stepper-content>

        <v-stepper-content step="2">
          <v-card flat class="mb-12">
            <pdf-sort-key-select-component
              :id="rekognitionResourcesId"
              @change-sort-key="changeSortKey"
              @close-overlay="closeOverlay"
            ></pdf-sort-key-select-component>
          </v-card>

          <v-btn color="primary" @click="sortPdf"> ソート実行 </v-btn>

          <v-btn text @click="step = 1"> ファイル再選択 </v-btn>
        </v-stepper-content>

        <v-stepper-content step="3">
          <v-card flat class="mb-12">
            <a
              v-if="sortedPdfPath"
              :href="sortedPdfPath"
              target="_blank"
              rel="noopener noreferrer"
              >ファイルがソートされました。</a
            >
          </v-card>

          <v-btn text @click="step = 2"> ファイル変更 </v-btn>
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

export default {
  components: {
    "pdf-sort-key-select-component": PdfSortKeySelectComponent,
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
      selectedSortKey: 0,
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
          this.openSnackbar(
            `アップロードに成功しました。: ${response.data.resource_original_name}`
          );
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

            this.openErrorSnackbar("システムエラーが発生しました。");
            this.closeOverlay();
          }
<<<<<<< HEAD
          this.closeOverlay();
=======
>>>>>>> e159bf0... 機能的には完成
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
          this.openSnackbar("ソートを開始しました。");

          console.log(response);
          this.step = 3;

          this.intervalId = setInterval(() => {
            console.log("getSortResult call");
            axios
              .post("/api/pdfsort/getSortResult", formData, {
                withCredentials: true,
              })
              .then((response) => {
                if (response.data.pdf_url) {
                  this.sortedPdfPath = response.data.pdf_url;
                  clearInterval(this.intervalId);
                  this.closeOverlay();
                }
              })
              .catch((error) => {});
          }, 15000);
        })
        .catch((error) => {
          let res = error.response;
          console.error(res);

          this.openErrorSnackbar("システムエラーが発生しました。");
          this.closeOverlay();
        });
    },
    changeSortKey: function (id) {
      this.selectedSortKey = id;
    },
    openErrorSnackbar: function (message) {
      this.snackbarColor = "error";
      this.openSnackbar(message);
    },
    openSnackbar: function (message) {
      this.snackbar = true;
      this.snackbarMessage = message;
    },
    closeOverlay: function () {
      this.overlay = false;
    },
  },
  watch: {
    step: function (newStep, oldStep) {
      if (newStep < oldStep) {
        if (newStep === 2) {
          this.pdf_url = null;
        } else if (newStep === 1) {
          this.rekognitionResourcesId = null;
        }
      }
    },
  },
};
</script>
