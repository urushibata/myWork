<template>
  <v-toolbar tabs flat>
    <v-toolbar-title>画像解析 (AWS Rekognition)</v-toolbar-title>
    <v-spacer></v-spacer>
    <template v-slot:extension>
      <v-tabs v-model="tabs" align-with-title>
        <v-tab to="/imageRekognition/detect"> 画像解析実行 </v-tab>
        <v-tab to="/imageRekognition/result"> 画像解析結果 </v-tab>
        <v-tabs-slider color="pink"></v-tabs-slider>
      </v-tabs>
    </template>
  </v-toolbar>
</template>

<script>
export default {
  data: function () {
    return {
      tabs: null,
    };
  },
};
</script>
