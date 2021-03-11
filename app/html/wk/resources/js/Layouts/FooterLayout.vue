<template>
  <v-footer padless>
    <v-card color="grey lighten-4" class="flex" flat tile>
      <v-card-title>
        <v-spacer></v-spacer>
        <div v-for="icon in icons" :key="icon.name">
          <v-btn text icon class="mx-4" :href="icon.url" target="_blank">
            <v-icon size="24px" :to="icon.url">
              {{ icon.name }}
            </v-icon>
          </v-btn>
        </div>
      </v-card-title>
    </v-card>
  </v-footer>
</template>

<script>
export default {
  data: () => ({
    icons: [
      { name: "mdi-twitter", url: "https://twitter.com/s_urushibata" },
      { name: "mdi-github", url: "https://github.com/urushibata/myWork" },
    ],
  }),
};
</script>
