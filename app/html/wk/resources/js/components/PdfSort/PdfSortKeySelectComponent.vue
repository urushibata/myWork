<template>
  <v-row v-show="imageDisplay" class="pa-6 my-6">
    <v-col cols="6">
      <v-img :src="imageSrc" contain>
        <div v-for="(b, i) in boundingBox" :key="b.i">
          <v-sheet
            class="frame"
            rounded
            outlined
            :style="boxStyle(b)"
            @click.stop="selectFrame(b.i)"
          />
        </div>
      </v-img>
    </v-col>
    <v-col cols="6">
      <v-row v-if="hasAnalysis">
        <image-rekognition-parser-component
          :detectType="'TextDetections'"
          :analysis="analysis"
          :selectedIndex="selectedIndex"
        />
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import RekognitionResult from "@/lib/RekognitionResult.js";
import ImageRekognitionParserComponent from "@/components/ImageRekognition/ImageRekognitionParserComponent";

export default {
  components: {
    "image-rekognition-parser-component": ImageRekognitionParserComponent,
  },
  props: {
    id: { type: Number },
  },
  data: function () {
    return {
      imageDisplay: false,
      imageSrc: null,
      boundingBox: null,
      intervalId: null,
      analysis: null,
      selectedIndex: 0,
    };
  },
  computed: {
    boxStyle: function () {
      return function (b) {
        const transparent = b.i === this.selectedIndex ? 0 : 0.4;
        const box = b.box;
        return {
          width: box.Width,
          height: box.Height,
          top: box.Top,
          left: box.Left,
          "background-color": `rgba(255, 255, 255, ${transparent})`,
        };
      };
    },
    hasAnalysis: function () {
      return this.analysis !== null && typeof this.analysis !== "undefined";
    },
  },
  methods: {
    getTopPageImage() {
      const formData = new FormData();
      formData.append("id", this.id);

      axios
        .post("/api/pdfSort/getTopPageDetect", formData, {
          withCredentials: true,
        })
        .then((response) => {
          const res = response.data;
          console.log(response);

          this.imageSrc = res.url;
          this.analysis = res.detect_result["TextDetections"];
          const imageObj = new Image();
          imageObj.src = this.imageSrc;
          imageObj.onload = () => {
            const result = new RekognitionResult(res.detect_result);
            this.boundingBox = result.createBoundingBox({
              imageObj: imageObj,
            });
            this.imageDisplay = true;
          };

          clearInterval(this.intervalId);
        })
        .catch((error) => {
          const res = error.response;

          if (res.status !== 200) {
            console.error(res);
            throw Error();
          }
        });
    },
    selectFrame(i) {
      this.selectedIndex = i ?? 0;
    },
  },
  watch: {
    id: function (newId, oldId) {
      console.log("new id :" + newId);
      if (newId) {
        this.imageSrc = null;
        this.intervalId = setInterval(() => {
          this.getTopPageImage();
        }, 15000);
      } else {
        this.imageSrc = null;
      }
    },
    selectedIndex: function (i) {
      this.$emit("change-sort-key", i);
    },
    imageDisplay: function (isDisplay) {
      if (isDisplay) {
        this.$emit("close-overlay");
      }
    },
  },
};
</script>

<style scoped>
.frame {
  border: double medium red !important;
  position: absolute !important;
}
</style>
