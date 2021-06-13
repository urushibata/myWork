<template>
  <div v-show="displayMessages">
    <v-alert
      :type="messageType"
      v-for="(message, i) in displayMessages"
      :key="i"
    >
      {{ message }}
    </v-alert>
  </div>
</template>
<script>
export default {
  computed: {
    messageType: function () {
      return this.$store.getters.messagesType;
    },
    displayMessages: function () {
      return this.$store.getters.messages;
    },
  },
};
</script>

