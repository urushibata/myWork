<template>
  <div>
    <v-treeview :items="parsedAnalysis" />
  </div>
</template>

<script>
export default {
  props: {
    detectType: {
      type: String,
      required: true,
    },
    analysis: {
      type: Array,
      required: true,
    },
    selectedIndex: {
      type: Number,
      required: true,
    },
  },
  computed: {
    parsedAnalysis: function () {
      if (this.analysis) {
        if (this.detectType === "FaceDetails") {
          const detectsResults = this.analysis[this.selectedIndex];
          const valueLabel = (value) => (value ? "" : "ではない");
          const genderJudge = (value) => (value === "Female" ? "女性" : "男性");
          const treeObject = [
            {
              name: `顔の確率: ${detectsResults.Confidence}%`,
            },
            {
              name: `年齢: ${detectsResults.AgeRange.Low} ~ ${detectsResults.AgeRange.High}歳`,
            },
            {
              name:
                "笑顔" +
                valueLabel(detectsResults.Smile.Value) +
                `: ${detectsResults.Smile.Confidence}%`,
            },
            {
              name:
                "眼鏡" +
                valueLabel(detectsResults.Eyeglasses.Value) +
                `: ${detectsResults.Eyeglasses.Confidence}%`,
            },
            {
              name:
                "サングラス" +
                valueLabel(detectsResults.Sunglasses.Value) +
                `: ${detectsResults.Sunglasses.Confidence}%`,
            },
            {
              name:
                "性別(" +
                genderJudge(detectsResults.Gender.Value) +
                `): ${detectsResults.Gender.Confidence}%`,
            },
            {
              name:
                "顎ひげ" +
                valueLabel(detectsResults.Beard.Value) +
                `: ${detectsResults.Beard.Confidence}%`,
            },
            {
              name:
                "口ひげ" +
                valueLabel(detectsResults.Mustache.Value) +
                `: ${detectsResults.Confidence}%`,
            },
            {
              name:
                "口が" +
                (detectsResults.EyesOpen.Value ? "開いている" : "閉じている") +
                `: ${detectsResults.EyesOpen.Confidence}%`,
            },
            {
              name:
                "目が" +
                (detectsResults.MouthOpen.Value ? "開いている" : "閉じている") +
                `: ${detectsResults.MouthOpen.Confidence}%`,
            },
            {
              name: "感情",
              children: detectsResults.Emotions.map((emotion, i) => {
                return {
                  id: i,
                  name: `${emotion.Type}: ${emotion.Confidence}%`,
                };
              }),
            },
          ];
          return treeObject.map((leaf, i) => {
            leaf.id = ++i;
            return leaf;
          });
        } else if (this.detectType == "Labels") {
          let id = 0;
          const target = this.analysis
            .map((ana) => {
              if ("Instances" in ana) {
                return ana.Instances.map((ins) => {
                  const leaf = {
                    id: id++,
                    name: `${ana.Name}: ${ins.Confidence}%`,
                    Parents: ana.Parents,
                  };
                  return leaf;
                });
              }
            })
            .flat()[this.selectedIndex];

          const parents = this.analysis
            .filter((ana) =>
              target.Parents.map((p) => p.Name).includes(ana.Name)
            )
            .map((ana) => {
              return { id: id++, name: `${ana.Name}: ${ana.Confidence}%` };
            });

          return [target].concat(parents);
        } else if (this.detectType == "TextDetections") {
          const detectsResults = this.analysis[this.selectedIndex];
          return [
            {
              id: 1,
              name: `${detectsResults.DetectedText}: ${detectsResults.Confidence}%`,
            },
            {
              id: 2,
              name: `Type: ${detectsResults.Type}`,
            },
          ];
        } else {
          console.info("パース対象外");
        }
      }
    },
  },
};
</script>
