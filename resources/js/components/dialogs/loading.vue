<template>
  <div class="text-center">
    <v-dialog
      v-model="loading"
      hide-overlay
      persistent
      width="450px"
      min-width="max-content"
    >
      <v-card color="black" dark style="margin-top: 0; margin-bottom: 0">
        <v-card-text class="text-center">
          Loading Please wait..
          <v-progress-linear
            indeterminate
            height="10"
            color="red"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["loading"],

  watch: {
    dialog(val) {
      if (!val) return;

      setTimeout(() => (this.dialog = false), 4000);
    },
  },
};
</script>
