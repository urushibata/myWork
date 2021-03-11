<template>
  <v-card flat>
    <v-card-title>プロフィール</v-card-title>

    <v-divider></v-divider>

    <v-card-text>
      <v-list-item>
        <v-list-item-avatar>
          <v-img :alt="profile.avatar.alt" :src="profile.avatar.src" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ profile.name }}</v-list-item-title>

          <v-divider></v-divider>

          <p v-for="(s, i) in profile.selfIntroduction" :key="i">{{ s }}</p>
        </v-list-item-content>
      </v-list-item>
    </v-card-text>
    <v-spacer></v-spacer>
    <v-card-text>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title>業務経験</v-list-item-title>

          <v-divider></v-divider>

          <div v-for="sg in profile.skillGroup" :key="sg.id">
            <v-list-item-subtitle>{{ sg.name }}</v-list-item-subtitle>
            <v-chip-group column>
              <v-chip v-for="s in sg.skill" :key="s.id" label>{{
                s.name
              }}</v-chip>
            </v-chip-group>
          </div>
        </v-list-item-content>
      </v-list-item>
    </v-card-text>

    <v-overlay :value="overlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-card>
</template>

<script>
export default {
  data: function () {
    return {
      profile: {
        name: null,
        selfIntroduction: null,
        avatar: {
          alt: null,
          src: null,
        },
        skillGroup: {},
      },
      overlay: true,
    };
  },
  created: function () {
/*
    axios
      .post("/api/getProfile", {}, { withCredentials: true })
      .then((response) => {
        const data = response.data;
        this.profile.name = data.profile.name;
        this.profile.selfIntroduction = data.profile.self_introduction.split(
          "\n"
        );
        this.profile.avatar.alt = data.profile.avatar_alt;
        this.profile.avatar.src = data.profile.avatar_src;
        this.profile.skillGroup = data.skill_group;

        this.overlay = false;
      });
*/
  },
};
</script>
