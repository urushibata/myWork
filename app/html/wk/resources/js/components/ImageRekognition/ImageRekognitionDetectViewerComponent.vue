<template>
  <v-row v-show="imageDisplay" class="pa-6 my-6">
    <v-col cols="6">
      <v-img :src="imageSrc">
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
      <v-card elevation="0" v-model="selectedIndex"
        ><canvas ref="canvas"
      /></v-card>
      <v-row v-if="analysis !== null">
        <image-rekognition-parser-component
          :detectType="detectType"
          :analysis="analysis"
          :selectedIndex="selectedIndex"
        />
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import ImageRekognitionParserComponent from "./ImageRekognitionParserComponent";

export default {
  components: {
    "image-rekognition-parser-component": ImageRekognitionParserComponent,
  },
  props: {
    detectResult: { type: Object },
    imageSrc: { type: String },
  },
  data: function () {
    return {
      imageDisplay: false,
      boundingBox: null,
      isLoaded: false,
      imageHight: null,
      imageWidth: null,
      analysis: null,
      selectedIndex: null,
    };
  },
  computed: {
    detectType: function () {
      if (this.detectResult) {
        return Object.keys(this.detectResult)[0];
      }
    },
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
  },
  watch: {
    selectedIndex: function (newIndex, oldIndex) {
      console.log("selectedIndex changed new:" + newIndex + " old:" + oldIndex);

      if (oldIndex !== null) {
        this.boundingBox[oldIndex].clear();
      }
      if (newIndex !== null) {
        this.boundingBox[newIndex].show();
      }
    },
    imageSrc: function (newImage, oldImage) {
      this.isLoaded = false;

      if (!!newImage) {
        this.imageDisplay = true;
      } else {
        this.imageDisplay = false;
      }
    },
    detectResult: function (newResult, oldResult) {
      if (newResult !== null && this.detectType !== null) {
        this.analysis = newResult[this.detectType];
      }
    },
    isLoaded: function (after, before) {
      if (after) {
        this.boundingBox[this.selectedIndex].show();
      }
    },
  },
  methods: {
    selectFrame: function (i) {
      this.selectedIndex = i ?? 0;
    },
    createCanvas: function () {
      console.log("called createCanvas");

      const context = this.$refs.canvas.getContext("2d");
      const imgObj = new Image();
      imgObj.src = this.imageSrc;
      imgObj.onload = () => {
        this.imageHight = imgObj.naturalHeight;
        this.imageWidth = imgObj.naturalWidth;
        this.isLoaded = true;
      };

      this.boundingBox = this.getBoundingBox().map((bb, i) => {
        const convertBoxsize = (len) => len * 100 + "%";
        const box = {};

        if (!bb) {
          return { i, box, show: () => {}, clear: () => {} };
        }

        box.Width = convertBoxsize(bb.Width);
        box.Height = convertBoxsize(bb.Height);
        box.Left = convertBoxsize(bb.Left);
        box.Top = convertBoxsize(bb.Top);

        const show = () => {
          context.drawImage(
            imgObj,
            bb.Left * this.imageWidth,
            bb.Top * this.imageHight,
            bb.Width * this.imageWidth,
            bb.Height * this.imageHight,
            0,
            0,
            bb.Width * this.imageWidth,
            bb.Height * this.imageHight
          );
        };

        const clear = () => {
          context.clearRect(
            0,
            0,
            bb.Width * this.imageWidth,
            bb.Height * this.imageHight
          );
        };

        return { i, box, show, clear };
      });
    },
    getBoundingBox: function () {
      const typeRoot = this.detectResult[this.detectType];

      if (this.detectType === "FaceDetails") {
        return typeRoot.BoundingBox;
      } else if (this.detectType == "Labels") {
        return typeRoot
          .map((label) => {
            return label.Instances.map((ins) => ins.BoundingBox);
          })
          .flat();
      } else if (this.detectType == "TextDetections") {
        return typeRoot.map((text) => {
          return text.Geometry.BoundingBox;
        });
      }
    },
  },
  beforeUpdate: function () {
    console.log("before update");
    this.selectFrame(this.selectedIndex);
    this.createCanvas();
  },
};
</script>
<style scoped>
.frame {
  border: double medium red !important;
  position: absolute !important;
}
</style>
