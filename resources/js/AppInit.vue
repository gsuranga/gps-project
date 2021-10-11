<template>
  <v-app id="inspire">
    <v-app-bar v-if="logedin" app flat dense color="red">
      <v-row class="text-center">
        <v-col class="d-flex justify-start">
          <v-app-bar-nav-icon
            v-if="isMobile"
            @click.stop="drawer = !drawer"
          ></v-app-bar-nav-icon>

          <v-img
            max-height="50px"
            max-width="150px"
            :src="'/images/dsiLogo.jpg'"
          ></v-img
        ></v-col>
        <v-col class="d-flex justify-center"> </v-col>
        <v-col class="d-flex justify-end"><profile></profile></v-col>
      </v-row>
    </v-app-bar>
    <v-main class="mt-6" app>
      <LoadingDialog :loading="loading"></LoadingDialog>
      <v-breadcrumbs :items="breadcrums" divider="/"></v-breadcrumbs>
      <v-overlay color="black" :value="overlay" opacity="0.8"> </v-overlay>
      <router-view></router-view>

      <MessageDialog
        :message="message"
        :messageStatus="messageStatus"
        :messageText="messageText"
        :showOverlay="showOverlay"
        @closeMessage="OnCloseMessage"
        @refesh="refreshThePage"
      ></MessageDialog>
      <permissionMessageDialog
        :message="permissionMessage"
        :messageStatus="permissionMessageStatus"
        :messageText="permissionMessageText"
        @closeMessage="permissionOnCloseMessage"
      ></permissionMessageDialog>
    </v-main>
    <v-footer v-if="logedin" padless color="red">
      <v-col class="text-center" cols="12">
        {{ new Date().getFullYear() }} — <strong>DSI</strong>
      </v-col>
    </v-footer>
  </v-app>
</template>


<script>
import profile from "./components/login/userProfile";
import LoadingDialog from "./components/dialogs/loading";
import MessageDialog from "./components/dialogs/message";
import permissionMessageDialog from "./components/dialogs/message";
import axios from "axios";
import CryptoJS from "crypto-js";

export default {
  name: "App",
  components: {
    profile,
    LoadingDialog,
    MessageDialog,
    permissionMessageDialog,
  },
  data() {
    return {
      breadcrums: [],
      isMobile: false,
      drawer: true,
      logedin: false,
      loading: false,
      showOverlay: false,
      pendingRequests: 0,

      message: false,
      messageStatus: false,
      messageText: "",

      permissionMessage: false,
      permissionMessageStatus: false,
      permissionMessageText: "",
    };
  },
  computed: {
    overlay: function () {
      if (this.loading == true || this.pendingRequests > 0 || this.showOverlay)
        return true;
      else return false;
    },
  },
  watch: {
    $route: function (to, from) {
      this.checkLogin();
      if (to.name == "SOBSA" && from.name == "LOGIN" && this.logedin) {
        this.loading = false;

        this.pendingRequests = 0;
      }
    },
  },
  async beforeMount() {
    this.checkLogin();
    axios.interceptors.request.use((config) => {
      this.showOverlay = false;
      this.checkLogin();
      if (this.logedin && config.url != "/api/user-group/my-permission") {
        this.loading = true;
        this.pendingRequests++;
      }
      return config;
    });

    await axios.interceptors.response.use(
      (response) => {
        this.showOverlay = false;
        this.checkLogin();
        if (response.config.url != "/api/user-group/my-permission") {
          this.pendingRequests--;
          if (this.pendingRequests === 0) {
            this.loading = false;
          }
          this.message = false;

          if (
            response.data.code === -1 &&
            response.data.msg &&
            response.config.method == "get"
          ) {
            this.permissionMessage = true;
            this.permissionMessageStatus = false;
            this.permissionMessageText = response.data.msg;
          }

          if (
            (response.data.code || response.data.msg) &&
            response.config.method != "get"
          ) {
            if (response.data.code === 0) {
              this.messageStatus = true;
              this.messageText = response.data.msg;
            } else {
              this.messageStatus = false;
              this.messageText = response.data.msg;
            }
            this.message = true;
          } else {
            this.message = false;
          }
        }
        this.checkLogout(response);
        return response;
      },
      (err) => {
        if (err.response.config.url != "/api/user-group/my-permission") {
          if (err.response.status == 401) {
            if (err.response.data.msg === "Unauthenticated.") {
              this.logedin = false;
              this.loading = false;

              localStorage.clear();
              this.$router.push({
                name: "LOGIN",
              });
            } else if (err.response.config.url == "/api/login") {
              this.message = true;
              this.messageStatus = false;
              this.messageText = "Invalid username or password";
            } else {
              this.checkPermissionError(err);
            }
          } else if (err.response.status == 500) {
            this.message = true;
            this.messageStatus = false;
            this.messageText = err.response.data.message;
            // this.messageText =
            //   "Something went wrong. Please try again. If the problem persists, contact your system administrator.";
            this.pendingRequests = 0;
            this.loading = false;
            this.showOverlay = true;
          } else {
            this.message = true;
            this.messageStatus = false;
            this.messageText =
              this.$router.currentRoute.path == "/login"
                ? "Unsuccessful Login Attempt!"
                : err.response.data.msg;
            this.pendingRequests = 0;
            this.loading = false;
          }
        }
      }
    );
  },
  methods: {
    checkPermissionError(err) {
      this.permissionMessage = true;
      this.permissionMessageStatus = false;
      this.permissionMessageText = "Additional Permission Required for - ";
      let url = err.response.config.url.split("/");

      let urlIndex = url.findIndex((f) => f == "api");

      this.permissionMessageText += url[urlIndex + 1]
        ? url[urlIndex + 1]
        : err.response.config.url;

      this.permissionMessageText += ". Please contact admin!";
      this.pendingRequests = 0;
      this.loading = false;

      this.$router.push({
        path: "/",
      });
    },
    checkLogout(res) {
      if (res.config.url == "/api/logout") {
        this.logedin = false;
        this.loading = false;

        localStorage.clear();
        this.$router.push({
          name: "LOGIN",
        });
      }
    },
    breadcrumRoute(value) {
      this.breadcrums = value;
    },
    changeIsMobile(value) {
      this.isMobile = value;
    },
    OnCloseMessage() {
      this.message = false;
    },
    refreshThePage() {
      this.message = false;
      this.$router.go();
    },
    permissionOnCloseMessage() {
      this.permissionMessage = false;
    },
    checkLogin() {
      if (this.$router.currentRoute.path != "/login") {
        let token = localStorage.getItem("token")
          ? localStorage.getItem("token")
          : null;

        let id = localStorage.getItem("userID")
          ? localStorage.getItem("userID")
          : null;

        let user = localStorage.getItem("user")
          ? CryptoJS.enc.Base64.parse(localStorage.getItem("user")).toString(
              CryptoJS.enc.Utf8
            )
          : null;

        let userGroup = localStorage.getItem("userGroup")
          ? CryptoJS.enc.Base64.parse(
              localStorage.getItem("userGroup")
            ).toString(CryptoJS.enc.Utf8)
          : null;

        let initial = localStorage.getItem("initial")
          ? CryptoJS.enc.Base64.parse(localStorage.getItem("initial")).toString(
              CryptoJS.enc.Utf8
            )
          : null;

        let UserType = localStorage.getItem("UserType")
          ? CryptoJS.enc.Base64.parse(
              localStorage.getItem("UserType")
            ).toString(CryptoJS.enc.Utf8)
          : null;

        let expiry = localStorage.getItem("expiry")
          ? CryptoJS.enc.Base64.parse(localStorage.getItem("expiry")).toString(
              CryptoJS.enc.Utf8
            )
          : null;

        if (
          token == null ||
          id == null ||
          user == null ||
          userGroup == null ||
          initial == null ||
          UserType == null ||
          expiry == null ||
          new Date().getTime() > expiry
        ) {
          this.logedin = false;
          localStorage.clear();
          this.$router.push({
            name: "LOGIN",
          });
        } else {
          this.logedin = true;
        }
      }
    },
  },
};
</script>

<style>
.navBar {
  margin: auto;
}

.arrow {
  border: solid gray;
  border-width: 0 3px 3px 0;
  display: inline-block;
  padding: 2px;
  margin: 2px;
}

.right {
  transform: rotate(-45deg);
  -webkit-transform: rotate(-45deg);
}

.down {
  transform: rotate(45deg);
  -webkit-transform: rotate(45deg);
}
</style>
