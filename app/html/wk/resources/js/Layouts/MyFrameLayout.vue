<template>
  <v-app>
    <v-app-bar app>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-banner>
        <router-link to="/">URUSHIBATA ポートフォリオ</router-link>
      </v-banner>

      <v-spacer />

      <span class="body-2">ログインユーザー：{{ LoginUserName }}</span>
    </v-app-bar>

    <v-navigation-drawer
      disable-resize-watcher
      v-model="drawer"
      fixed
      temporary
      width="50vw"
    >
      <my-profile-component />
    </v-navigation-drawer>

    <v-main>
      <v-container>
        <Breadcrumbs />
        <router-view />
      </v-container>
    </v-main>

    <footer-component />
  </v-app>
</template>

<script>
import MyProfileComponent from "@/components/MyProfileComponent";

export default {
  components: {
    "my-profile-component": MyProfileComponent,
  },
  data: function () {
    return {
      drawer: false,
    };
  },
  props: {
    userName: { type: String },
  },
  computed: {
    LoginUserName() {
      return this.$store.state.userName;
    },
  },
  created: function () {
    this.$store.commit("setUserName", this.userName);
  },
};
</script>
