
<template>
  <div>
    <v-row class="fill-height">
      <template v-for="lg in linkGroups">
        <v-col :key="lg.group" class="mt-2" cols="12">
          <strong>{{ lg.group }}</strong>
        </v-col>

        <v-col v-for="(ll, i) in lg.links" :key="ll.label" cols="6" md="2">
          <v-hover v-slot="{ hover }">
            <v-card
              light
              class="mx-n1"
              max-width="200px"
              max-height="300px"
              :elevation="hover ? 10 : 2"
              :class="{ 'on-hover': hover }"
            >
              <v-img
                class="ml-auto"
                max-width="200px"
                max-height="300px"
                :src="`https://picsum.photos/200/300/?random=${i}&blur=2`"
              >
                <template v-slot:placeholder>
                  <v-row
                    class="fill-height ma-0"
                    align="center"
                    justify="center"
                  >
                    <v-progress-circular
                      indeterminate
                      color="amber"
                    ></v-progress-circular>
                  </v-row>
                </template>

                <v-fade-transition>
                  <v-overlay v-if="hover" absolute color="#036358">
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" :to="ll.url">{{
                          ll.label
                        }}</v-btn>
                      </template>
                      {{ ll.description }}
                    </v-tooltip>
                  </v-overlay>
                </v-fade-transition>
              </v-img>
            </v-card>
          </v-hover>
        </v-col>
      </template>
    </v-row>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      linkGroups: [
        {
          group: "アプリケーション",
          links: [
            {
              url: "/imageRekognition/detect",
              label: "画像解析",
              description:
                "AWS Rekognition APIを利用して画像の解析を行います。",
            },
            {
              url: "/pdfSort/",
              label: "PDF並び変え",
              description:
                "AWS Rekognition APIを利用してPDFの帳票をファイル内でソートします。",
            },
          ],
        },
      ],
    };
  },
};
</script>

<style scoped>
.v-card:not(.on-hover) {
  opacity: 0.8;
}
</style>
