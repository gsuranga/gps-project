<template>
  <div class="text-center">
    <v-dialog
      v-model="message"
      persistent
      width="450px"
      min-width="max-content"
    >
      <v-card style="margin-top: 0; margin-bottom: 0">
        <v-card-title class="text-center">
          <v-icon
            v-if="messageStatus"
            x-large
            color="green "
            justify="space-around"
          >
            mdi-checkbox-marked-circle
          </v-icon>

          <v-icon
            v-if="!messageStatus"
            x-large
            color="red "
            justify="space-around"
          >
            mdi-close-circle
          </v-icon>
        </v-card-title>
        <v-card-text>{{ messageText }}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn v-if="showOverlay" text @click="onClickRefreshButton">
            Refresh the Page
          </v-btn>
          <v-btn text @click="onClickCloseButton"> Okay </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["message", "messageStatus", "messageText", "showOverlay"],
  methods: {
    onClickCloseButton(event) {
      this.$emit("closeMessage");
    },

    onClickRefreshButton(event) {
      this.$emit("refesh");
    },
  },
};
</script>
