<template>
  <v-card>
    <image-rekognition-tabs-component />
    <v-card class="pa-6 my-6" elevation="0">
      <v-sheet color="white" elevation="1" class="pa-2">
        <v-card-text
          >指定の画像をAWS Rekognitionを使用して分析します。<br />
          解析する画像(png,
          jpeg)と解析の種類を選択して画像解析ボタンをクリックしてください。</v-card-text
        >
      </v-sheet>
      <v-row>
        <v-col class="d-flex" cols="12" sm="6">
          <v-file-input
            counter
            multiple
            show-size
            truncate-length="50"
            accept="image/png, image/jpeg"
            v-on:change="fileSelected"
            :error="errors.uploadFiles"
            :error-messages="messages.uploadFiles"
          ></v-file-input>
        </v-col>
      </v-row>
      <v-row>
        <v-col class="d-flex" cols="12" sm="3">
          <v-select
            v-model="selectedRekognitionType"
            label="解析の種類"
            return-object
            :items="rekognitionTypes"
            :hint="selectedRekognitionType.explanation"
            persistent-hint
            :error="errors.rekognitionType"
            :error-messages="messages.rekognitionType"
          ></v-select>
        </v-col>
      </v-row>
      <v-row>
        <v-col class="d-flex" cols="12" sm="3">
          <v-btn color="primary" v-on:click="fileUpload">画像解析開始</v-btn>
        </v-col>
      </v-row>
    </v-card>

    <v-card elevation="0">
      <v-row class="fill-height">
        <div v-for="(f, i) in fileInfo" class="pa-6">
          <v-col :key="i" cols="12" justify="center">
            <v-hover v-slot="{ hover }">
              <v-card
                :elevation="hover ? 12 : 2"
                :class="{ 'on-hover': hover }"
              >
                <v-img
                  position="top"
                  :src="f.selectedImageUrl"
                  max-width="150"
                  max-height="150"
                />
                <v-card-subtitle>
                  {{ f.imageName }}
                </v-card-subtitle>
              </v-card>
            </v-hover>
          </v-col>
        </div>
      </v-row>
    </v-card>

    <snackbar-component
      :snackbar="snackbar"
      :message="snackbarMessage"
      :multi-line="true"
    >
      <template v-slot:link
        ><v-btn text color="yellow lighten-2" to="/imageRekognition/result"
          >解析結果を確認する</v-btn
        ></template
      >
    </snackbar-component>
    <v-overlay :value="overlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-card>
</template>

<script>
import ImageRekognitionTabsComponent from "./ImageRekognitionTabsComponent";

export default {
  components: {
    "image-rekognition-tabs-component": ImageRekognitionTabsComponent,
  },
  data: function () {
    return {
      selectedRekognitionType: null,
      rekognitionTypes: [
        {
          value: "faces",
          text: "顔検出と分析",
          explanation: "画像の中から人の顔とその特徴を抽出します。",
        },
        {
          value: "text",
          text: "テキスト検出",
          explanation: "画像の中からテキストを抽出します。日本語未対応です。",
        },
        {
          value: "labels",
          text: "ラベル",
          explanation: "画像の中からラベリング可能なものを抽出します。",
        },
      ],
      fileInfo: [],
      errors: {
        uploadFiles: false,
        rekognitionType: false,
      },
      messages: {
        uploadFiles: null,
        rekognitionType: null,
      },
      snackbar: false,
      snackbarMessage: "",
      snackbarlink: "",
      overlay: false,
    };
  },
  methods: {
    fileSelected(event) {
      this.fileInfo = [];
      if (Object.keys(event).length !== 0) {
        const that = this;
        event.forEach((selectedFile) => {
          const reader = new FileReader();
          reader.onload = (e) => {
            that.fileInfo.push({
              selectedImageUrl: e.target.result,
              loadedImage: selectedFile,
              imageName: selectedFile.name,
            });
          };

          reader.readAsDataURL(selectedFile);
        });
      }
    },
    fileUpload() {
      const formData = new FormData();
      Array.from(this.fileInfo, (f, i) =>
        formData.append(`uploadFiles[${i}]`, f.loadedImage)
      );
      formData.append("rekognitionType", this.selectedRekognitionType.value);

      this.overlay = true;
      axios
        .post("/api/imageRekognition/fileupload", formData, {
          withCredentials: true,
        })
        .then((response) => {
          console.log("success: ", response);

          response.data.forEach((res) => {
            this.snackbar = true;
            this.snackbarMessage = `アップロードに成功しました。: ${res.resource_original_name}`;
          });

          this.fileInfo = [];
        })
        .catch((error) => {
          console.error("error: ", error);
          let res = error?.response;

          if (res?.status == 422) {
            Object.keys(res.data.errors).forEach((key) => {
              this.errors[key] = true;
              this.messages[key] = res.data.errors[key];
            });
          } else {
            this.$store.dispatch("setMessages", "error");
          }
        })
        .finally(() => (this.overlay = false));
    },
  },
  created: function () {
    this.selectedRekognitionType = this.rekognitionTypes[1];
  },
};
</script>
