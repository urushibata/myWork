
<template>
  <div>
    <my-profile-component :profile="profile" />

    <v-row>
      <template v-for="lg in linkGroups">
        <v-col :key="lg.group" class="mt-2" cols="12">
          <strong>{{ lg.group }}</strong>
        </v-col>

        <v-col v-for="ll in lg.links" :key="ll.label" cols="6" md="2">
          <v-card class="d-flex align-center" light height="100" :to="ll.url">
            <v-img
              src="https://3.bp.blogspot.com/-4ZXrEpcG74A/WlGpFb87TQI/AAAAAAABJkU/22NohgkRDOIQeh_V4QeDE0yVtBvUheRLACLcBGAs/s800/ai_image_gazou_ninshiki.png"
            />
            <v-card-title>{{ ll.label }}</v-card-title>

            <v-scroll-y-transition>
              <div class="display-3 flex-grow-1 text-center"></div>
            </v-scroll-y-transition>
          </v-card>
        </v-col>
      </template>
    </v-row>
  </div>
</template>

<script>
export default {
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.initialize();
      next();
    });
  },
  data: () => {
    return {
      linkGroups: [
        {
          group: "アプリケーション",
          links: [{ url: "/imageRekognition/detect", label: "画像解析" }],
        },
      ],
      profile: {
        name: "漆畑　真也",
        selfIntroduction: "",
        privateActivities: {
          title: "Coder Dojo",
          Comment: "",
          links: [
            {
              name: "Coder Dojo 三島沼津",
              url: "https://coderdojo-mn.com/",
            },
            {
              name: "Coder Dojo 静岡",
              url: "https://coderdojo-shizuoka.org/",
            },
          ],
        },
        avatar: {
          alt: "urushibata avatar",
          src:
            "https://scontent.fngo4-1.fna.fbcdn.net/v/t1.0-9/76609813_121552215926001_5034201832738521088_n.jpg?_nc_cat=101&ccb=2&_nc_sid=09cbfe&_nc_ohc=Gwll3HmJanUAX_oWtY6&_nc_ht=scontent.fngo4-1.fna&oh=e144c5d385542bf6911872eb6d2c6565&oe=6039C948",
        },
        skillSet: {},
      },
    };
  },
  methods: {
    initialize() {
      axios.post("/getProfile", {}).then((response) => {
        console.log(response);
      });
    },
  },
};
</script>
