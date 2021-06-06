<template>
  <v-footer padless absolute>
    <v-card color="grey lighten-4" class="flex" flat tile>
      <v-card-title>
        <v-dialog
          v-model="dialog"
          fullscreen
          hide-overlay
          scrollable
          transition="dialog-bottom-transition"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn v-bind="attrs" v-on="on" text tile>
              このサイトについて
            </v-btn>
          </template>

          <v-card>
            <v-toolbar flat tile>
              <v-btn icon @click="dialog = false">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar>
            <v-sheet class="pa-3" tile>
              <v-img
                :src="getImageUrl(0)"
                contain
                :max-height="images[0].height"
                :max-width="images[0].width"
                class="pa-3"
                @click="toggleResize(0)"
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
              </v-img>
            </v-sheet>

            <v-sheet class="fill-height" tile>
              <v-list>
                <v-list-item three-line>
                  <v-list-item-content>
                    <v-list-item-title>FW, ライブラリー</v-list-item-title>
                    <v-list-item-subtitle
                      >laravel8, Vue.js 2.5, Vuetify, Vuex, Vue Router,
                      pdf2image</v-list-item-subtitle
                    >
                    <v-divider />
                    <v-list-item-title>プログラミング言語</v-list-item-title>
                    <v-list-item-subtitle
                      >PHP7.4, Javascript, Python3.8</v-list-item-subtitle
                    >
                    <v-divider />
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>Web Server</v-list-item-title>
                    <v-list-item-subtitle>Nginx</v-list-item-subtitle>
                    <v-divider />
                    <v-list-item-title>DB</v-list-item-title>
                    <v-list-item-subtitle>MySQL</v-list-item-subtitle>
                    <v-divider />
                    <v-list-item-title>AWS</v-list-item-title>
                    <v-list-item-subtitle
                      >ECS Fargate, ELB, S3, Cloud Front, DynamoDb, ACM, EFS,
                      Lambda, SQS, SNS, Rekognition, EventBridge, Route53,
                      ECR</v-list-item-subtitle
                    >
                    <v-divider />
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>
                    <v-card elevation="0" tile class="pa-3">
                      このアプリケーションはフロントはVue.jsでバックエンドはLaravel8で構築したSPAです。<br />
                      画像解析サービスを使用して何かできないかと興味があったので作成しました。画像解析サービスにはAWS
                      Rekognitionを使っています。
                    </v-card>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
              <v-list>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>画像解析</v-list-item-title>
                    <v-sheet class="pa-3" tile>
                      <v-img
                        :src="getImageUrl(1)"
                        contain
                        :max-height="images[1].height"
                        :max-width="images[1].width"
                        class="pa-3"
                        @click="toggleResize(1)"
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
                      </v-img>
                      <v-card elevation="0" tile class="pa-3">
                        アップロードされたファイルはS3に格納されます。それをトリガーにLambdaが起動し、Rekognitionで画像解析します。<br />
                        解析結果は、SQSを仲介しDynamoDBに登録されます。<br />
                        SNS,
                        SQSは各サービスを疎結合にするために挟んでいます。<br />
                        クライアントはポーリングし、処理が終わったら画面に表示します。
                      </v-card>
                    </v-sheet>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
              <v-list>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>PDF並び替え</v-list-item-title>
                    <v-sheet class="pa-3" tile>
                      <v-img
                        :src="getImageUrl(2)"
                        contain
                        :max-height="images[2].height"
                        :max-width="images[2].width"
                        class="pa-3"
                        @click="toggleResize(2)"
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
                      </v-img>
                      <v-card elevation="0" tile class="pa-3">
                        アップロードされたファイルはS3に格納されます。それをトリガーにLambdaがECS
                        Fargateを起動します。<br />
                        FargateではPythonでPDFの１ページ目を画像に変換し、それをS3に格納します。
                        Fargateを使用しているのはPythonの画像変換ライブラリーのpdf2imageが<a
                          href="https://poppler.freedesktop.org/"
                          target="_blank"
                          rel="noopener noreferrer"
                          >Poppler</a
                        >というツールを内部的に利用しておりサーバレス環境(Lambda)では難しいためです。<br />
                        格納された画像は上記の画像解析処理によって解析されます。
                        クライアントはポーリングし処理が終わるのを待ちます。
                        <v-spacer />
                        解析結果が表示されソートに使用するキーを選択するとSNSを介してECS
                        Fargateが起動し、PDFをソートします。ソートされたPDFはS3に保存されます。
                        処理が完了するとEventBridgeで検知し、SNSからHTTP通知をWebサーバーに送信します。<br />
                        クライアントにはファイルのリンクが表示されます。
                      </v-card>
                    </v-sheet>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
            </v-sheet>
          </v-card>
        </v-dialog>

        <v-spacer></v-spacer>
        <v-btn
          v-for="icon in icons"
          :key="icon.name"
          text
          icon
          class="mx-4"
          :href="icon.url"
          target="_blank"
          rel="noopener noreferrer"
          :color="icon.color"
        >
          <v-icon size="24px" :to="icon.url">
            {{ icon.name }}
          </v-icon>
        </v-btn>
      </v-card-title>
    </v-card>
  </v-footer>
</template>

<script>
export default {
  data: function () {
    return {
      dialog: false,
      icons: [
        //{ name: "mdi-twitter", url: "https://twitter.com/s_urushibata" },
        { name: "mdi-github", url: "https://github.com/urushibata/myWork" },
        { name: "mdi-alpha-q-box", url: "https://qiita.com/urushibata", color: "green"},
      ],
      images: [
        {
          name: "mywork-rekognition-main.png",
          defaultHeight: "40vh",
          defaultWidth: "50vw",
          height: "40vh",
          width: "50vw",
        },
        {
          name: "mywork-rekognition-image-rekognition.png",
          defaultHeight: "40vh",
          defaultWidth: "50vw",
          height: "40vh",
          width: "50vw",
        },
        {
          name: "mywork-rekognition-pdf-sort.png",
          defaultHeight: "40vh",
          defaultWidth: "50vw",
          height: "40vh",
          width: "50vw",
        },
      ],
    };
  },
  computed: {},
  methods: {
    getImageUrl(index) {
      return `${this.$store.getters.imageUrl}General/${this.images[index].name}`;
    },
    toggleResize(imageIndex) {
      const images = this.images[imageIndex];

      if (images.defaultWidth === images.width) {
        images.width = "100vw";
        images.height = "100vh";
      } else {
        images.width = images.defaultWidth;
        images.height = images.defaultHeight;
      }
    },
  },
};
</script>
