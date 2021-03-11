<template>
  <v-card>
    <image-rekognition-tabs-component />
    <v-row>
      <v-col cols="8">
        <v-card class="pa-6 my-6" elevation="0">
          <v-data-table
            dense
            :headers="headers"
            :items="results"
            item-key="path"
            class="elevation-1"
            @click:row="detailDisplay"
            :loading="listLoading"
            loading-text="Loading... Please wait"
            no-data-text="No data"
          >
            <template v-slot:item.delete_icon="{ item }">
              <v-btn text icon color="lighten-2">
                <v-icon @click.stop="removeImage(item)">mdi-delete</v-icon>
              </v-btn>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
    <image-rekognition-detect-viewer-component
      :imageSrc="imageSrc"
      :detectResult="detectResult"
    />

    <v-overlay :value="imageLoading">
      <v-progress-circular
        indeterminate
        color="amber"
        size="64"
      ></v-progress-circular>
    </v-overlay>

    <snackbar-component
      :snackbar="snackbar"
      :message="snackbarMessage"
      :color="snackbarColor"
      @snackbarChanged="snackbarChanged"
    />
  </v-card>
</template>

<script>
import ImageRekognitionTabsComponent from "./ImageRekognitionTabsComponent";
import ImageRekognitionDetectViewerComponent from "./ImageRekognitionDetectViewerComponent";

export default {
  components: {
    "image-rekognition-tabs-component": ImageRekognitionTabsComponent,
    "image-rekognition-detect-viewer-component": ImageRekognitionDetectViewerComponent,
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.initialize();
      next();
    });
  },
  data: function () {
    return {
      listLoading: true,
      results: [],
      headers: [
        {
          text: "ファイル名",
          value: "name",
        },
        { text: "解析タイプ", value: "rekognition_type" },
        { text: "アップロード時間", value: "upload_time" },
        { text: "", value: "delete_icon", sortable: false },
      ],
      imageSrc: null,
      detectResult: null,
      errorMessage: null,
      imageLoading: false,
      snackbar: false,
      snackbarMessage: "",
      snackbarColor: "",
    };
  },
  methods: {
    initialize() {
      axios
        .post("/api/imageRekognition/getList", {}, { withCredentials: true })
        .then((response) => {
          this.results = response.data;
          this.listLoading = false;
        });
    },
    detailDisplay(row) {
      this.imageLoading = true;
      this.imageSrc = null;
      axios
        .get(`/api/rekognition_resource/${row.id}`, { withCredentials: true })
        .then((response) => {
          this.imageSrc = row.path;
          this.detectResult = response.data;
        })
        .catch((error) => {
          let res = error.response;
          console.error(
            `解析の詳細の表示でエラーが発生しました。: ${row.path}`
          );

          this.imageSrc = null;
          this.detectResult = null;
          if (res.status == 422) {
            this.snackbarMessage = res.data.errors;
          } else {
            this.snackbarMessage = "システムエラーが発生しました。";
          }

          this.snackbar = true;
          this.snackbarColor = "error";
        })
        .finally(() => {
          this.imageLoading = false;
        });
    },
    removeImage(row) {
      this.listLoading = true;
      axios
        .delete(`/api/rekognition_resource/${row.id}`, {
          withCredentials: true,
        })
        .then((response) => {
          this.results = this.results.filter(
            (obj) => obj.id !== response.data.id
          );
        })
        .catch((error) => {
          console.error(error.response);
          this.errorMessage = error.response.data.message;
        })
        .finally(() => {
          this.listLoading = false;
        });
    },
    snackbarChanged(display) {
      this.snackbar = display;
    },
  },
};
</script>
