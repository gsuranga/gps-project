<template>
  <v-menu bottom min-width="200px" rounded offset-y>
    <template v-slot:activator="{ on }">
      <v-btn icon x-large v-on="on">
        <v-avatar color="black" size="48">
          <span class="white--text headline">{{ initial }}</span>
        </v-avatar>
      </v-btn>
    </template>
    <v-card>
      <v-list-item-content class="justify-center">
        <div class="mx-auto text-center">
          <h4 class="ma-2">Hello, {{ user }} !</h4>
          <v-divider class="my-3"></v-divider>

          <v-btn color="error" elevation="2" raised text @click="logout">
            Log Out
          </v-btn>
        </div>
      </v-list-item-content>
    </v-card>
  </v-menu>
</template>

<script>
import CryptoJS from "crypto-js";
export default {
  data() {
    return {
      user: null,
      initial: null,
    };
  },
  mounted() {
    this.user = CryptoJS.enc.Base64.parse(
      localStorage.getItem("user")
    ).toString(CryptoJS.enc.Utf8);
    this.initial = CryptoJS.enc.Base64.parse(localStorage.getItem("initial"))
      .toString(CryptoJS.enc.Utf8)
      .split(" ")
      .map((n) => n[0])
      .join(".")
      .substring(0, 3);
  },
  methods: {
    logout() {
      axios.post("/api/logout");
    },
  },
};
</script>

<style></style>
