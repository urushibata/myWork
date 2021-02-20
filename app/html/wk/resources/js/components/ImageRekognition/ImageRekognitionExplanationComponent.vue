<template>
  <v-dialog v-model="dialog" width="600px">
    <template v-slot:activator="{ on, attrs }">
      <v-btn color="info" dark v-bind="attrs" v-on="on"> 詳しい説明 </v-btn>
    </template>
    <v-card>
      <v-card-title>
        <span class="headline">処理フロー</span>
      </v-card-title>
      <v-card-text> </v-card-text>
      <v-card-text>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="green darken-1" text @click="dialog = false">
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  data() {
    return {
      dialog: false,
    };
  },
};
</script>
