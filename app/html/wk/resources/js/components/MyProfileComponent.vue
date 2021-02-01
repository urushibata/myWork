<template>
  <v-card>
    <v-card-title>プロフィール</v-card-title>
    <v-card-text>
      <v-list-item>
        <v-list-item-avatar>
          <v-img :alt="profile.avatar.alt" :src="profile.avatar.src" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title>{{ profile.name }}</v-list-item-title>
          <v-list-item-subtitle>{{
            profile.selfIntroduction
          }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-card-text>

    <v-card-text>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title>スキル</v-list-item-title>
          <div v-for="s in profile.skillSet" :key="s.id">
            <v-list-item-subtitle>{{ s.type }}</v-list-item-subtitle>
            <v-chip-group>
              <div v-for="ss in s.set" :key="ss">
                <v-chip class="ma-2">{{ ss }}</v-chip>
              </div>
            </v-chip-group>
          </div>
        </v-list-item-content>
      </v-list-item>
    </v-card-text>

    <v-card-text>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title>社外活動</v-list-item-title>
          <v-list-item-subtitle>{{ profile.privateActivities.title}} {{ profile.privateActivities.Comment }}</v-list-item-subtitle>
          <div v-for="link in profile.privateActivities.links" :key="link.name" >
              <a :href="link.url" target="_blank" rel="noopener noreferrer">{{link.name}}</a>
          </div>
        </v-list-item-content>
      </v-list-item>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: {
    profile: Object,
  },
  mounted() {
    console.debug(this.profile);
  },
};
</script>
