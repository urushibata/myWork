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
      <v-card elevation="0" v-model="selectedIndex">
          <canvas ref="canvas" />
      </v-card>
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
      /*
       * 0: not load
       * 1: now loading
       * 2: loaded
       */
      loadState: 0,
      iamgeObj: null,
      imageContext: null,
      imageHight: 0,
      imageWidth: 0,
      analysis: null,
      selectedIndex: 0,
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

      if (this.boundingBox !== null) {
        if (oldIndex !== null) {
          this.boundingBox[oldIndex].clear();
        }
        if (newIndex !== null) {
          this.boundingBox[newIndex].show();
        }
      }
    },
    imageSrc: function (newImage, oldImage) {
      if (this.imageContext) {
        this.imageContext.clearRect(
          0,
          0,
          this.imageContext.canvas.width,
          this.imageContext.canvas.height
        );
      }
      this.loadState = 0;
      this.imageObj = null;
      this.imageContext = null;
      this.imageWidth = 0;
      this.imageHight = 0;
      this.boundingBox = null;
      this.selectedIndex = 0;

      if (!!newImage) {
        this.imageDisplay = true;
        this.createCanvas();
      } else {
        this.imageDisplay = false;
      }
    },
    detectResult: function (newResult, oldResult) {
      if (newResult !== null && this.detectType !== null) {
        this.analysis = newResult[this.detectType];
      }
    },
    loadState: function (after, before) {
      if (after === 2) {
        this.createBoundingBox();
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

      this.loadState = 1;
      this.imageContext = this.$refs.canvas.getContext("2d");
      this.imageObj = new Image();
      this.imageObj.src = this.imageSrc;
      this.imageObj.onload = () => {
        this.loadState = 2;
        this.imageHight = this.imageObj.naturalHeight;
        this.imageWidth = this.imageObj.naturalWidth;
      };
    },
    createBoundingBox: function () {
      this.boundingBox = this.getBoundingBox().map((bb, i) => {
        const convertBoxsize = (len) => len * 100 + "%";
        const box = {
          Width: convertBoxsize(bb.Width),
          Height: convertBoxsize(bb.Height),
          Left: convertBoxsize(bb.Left),
          Top: convertBoxsize(bb.Top),
        };

        const show = () => {
          this.$refs.canvas.width = bb.Width * this.imageWidth;
          this.$refs.canvas.height = bb.Height * this.imageHight;

          this.imageContext.drawImage(
            this.imageObj,
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
          this.imageContext.clearRect(
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
        return typeRoot.map((face) => face.BoundingBox);
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
};
</script>
<style scoped>
.frame {
  border: double medium red !important;
  position: absolute !important;
}
</style>
