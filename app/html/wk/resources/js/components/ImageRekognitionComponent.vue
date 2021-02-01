<template>
  <div>
    <h1>Image Rekognition</h1>
    <v-file-input
      counter
      multiple
      show-size
      truncate-length="50"
      accept="image/png, image/jpeg"
      v-on:change="fileSelected"
    ></v-file-input>
    <v-btn color="primary" v-on:click="fileUpload">画像解析開始</v-btn>
    <img id="preview" />
  </div>
</template>

<script>
export default {
  components: {},
  data: function () {
    return {
      fileInfo: "",
    };
  },
  methods: {
    fileSelected(e) {
      if (Object.keys(e).length !== 0) {
        const uploadFile = e[0];

        const reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById("preview").src = e.target.result ?? "";
        };

        this.fileInfo = uploadFile;
        reader.readAsDataURL(uploadFile);
      } else {
        document.getElementById("preview").src = "";
      }
    },
    fileUpload() {
      const formData = new FormData();

      formData.append("file", this.fileInfo);

      axios.post("/imageRekognition/fileupload", formData).then((response) => {
        console.log(response);
      });
    },
  },
};
</script>
