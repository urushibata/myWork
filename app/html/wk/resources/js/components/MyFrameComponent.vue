<template>
  <v-app>
    <v-app-bar app>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-banner>
        <router-link to="/">URUSHIBATA ポートフォリオ</router-link>
      </v-banner>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer" fixed temporary>
      <!--  -->
    </v-navigation-drawer>

    <v-main>
      <v-container>
        <router-view />
      </v-container>
    </v-main>

    <footer-component />
  </v-app>
</template>

<script>
export default {
  props: {
    data: ["data"],
  },
  data: () => ({ drawer: null }),
};
</script>
