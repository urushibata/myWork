
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
var skill = [
  {
    id: 1,
    type: "言語",
    set: ["Java", "C#", "PHP", "JavaScript", "HTML", "CSS", "Python"],
  },
  {
    id: 2,
    type: "フレームワーク、ライブラリ",
    set: [
      "Struts",
      "Spring",
      "CakePHP",
      "Laravel",
      "Smarty",
      "JQuery",
      "Vue.js",
      "Bootstrap",
    ],
  },
  {
    id: 3,
    type: "DB",
    set: ["Oracle", "SQL Server", "MySQL"],
  },
  {
    id: 4,
    type: "テスト",
    set: ["JUnit", "NUnit", "PHPUnit"],
  },
  {
    id: 5,
    type: "ソース管理",
    set: ["Git", "Svn", "TFS"],
  },
  {
    id: 6,
    type: "AWS",
    set: [
      "EC2",
      "IAM",
      "VPC",
      "RDB",
      "Lambda",
      "FSx for Windows",
      "Codecommit",
      "DMS",
      "Cloud watch",
      "Cloud Trail",
    ],
  },
  {
    id: 7,
    type: "管理ツール",
    set: ["Jira", "Trello", "Confluence"],
  },
];

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
        skillSet: skill,
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
