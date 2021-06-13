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

    <v-main :class="mainMb">
      <v-container>
        <Breadcrumbs />
        <message />
        <router-view />
      </v-container>
    </v-main>

    <footer-component ref="footer" />
  </v-app>
</template>

<script>
import MyProfileComponent from "@/components/MyProfileComponent";
import Message from "../components/Controll/Message";

export default {
  components: {
    "my-profile-component": MyProfileComponent,
    message: Message,
  },
  data: function () {
    return {
      drawer: false,
      mainMb: null,
    };
  },
  computed: {
    LoginUserName() {
      return this.$store.getters.userName;
    },
  },
  methods: {
    bottomMargin() {
      let footerMarginSize = Math.ceil(this.$refs.footer.$el.clientHeight / 4);
      if (footerMarginSize > 16) {
        footerMarginSize = 16;
      }

      this.mainMb = `mb-${footerMarginSize}`;
    },
  },
  created: function () {
    this.$store.dispatch("init");
  },
  mounted: function () {
    this.bottomMargin();

    this.$store.subscribeAction({
      before: (action, state) => {
        if (action.type === "setMessages") {
          console.log(
            action.payload
          );
        }
      },
    });
  },
};
</script>
