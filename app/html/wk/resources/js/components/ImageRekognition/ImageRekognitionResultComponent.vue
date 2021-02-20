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
            @click:row="clickRow"
            :loading="nowLoading"
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
      nowLoading: true,
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
    };
  },
  methods: {
    initialize() {
      axios.post("/imageRekognition/getList", {}).then((response) => {
        this.results = response.data;
        this.nowLoading = false;
      });
    },
    clickRow(row) {
      axios
        .get("/api/rekognition_resource/" + row.id)
        .then((response) => {
          this.imageSrc = row.path;
          this.detectResult = response.data;
        })
        .catch((error) => {
          this.imageSrc = null;
          this.detectResult = null;
          if (error.request.status == 422) {
            console.log("画像の取得に失敗しました。:" + row.path);
          }
        });
    },
    removeImage(row) {
      this.nowLoading = true;
      axios
        .delete("/api/rekognition_resource/" + row.id)
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
          this.nowLoading = false;
        });
    },
  },
};
</script>
