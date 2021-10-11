<template>
  <div>
    <div class="login" max-width="90%">
      <v-img
        class="img"
        max-height="100px"
        max-width="300px"
        :src="'/images/dsiLogo.jpg'"
      ></v-img>
      <div class="boxModel">
        <div class="FormModel">
          <v-form v-model="valid" lazy-validation ref="form">
            <v-row class="text-center">
              <v-col cols="12" sm="12">
                <v-text-field
                  hideDetails
                  solo
                  v-model="username"
                  :counter="50"
                  :rules="inputRules"
                  label="Username"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row class="text-center">
              <v-col cols="12" sm="12">
                <v-text-field
                  hideDetails
                  solo
                  v-model="password"
                  :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                  :rules="inputRules"
                  :type="show1 ? 'text' : 'password'"
                  name="input-10-1"
                  label="Password"
                  hint="At least 8 characters"
                  counter
                  @click:append="show1 = !show1"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
          <v-row class="text-center">
            <v-col cols="12" sm="12">
              <v-btn
                block
                :disabled="axiosLoading"
                dark
                color="black"
                @click="submit"
                >Login</v-btn
              >
            </v-col>
          </v-row>
          <v-row class="text-right">
            <v-col cols="12" sm="12"
              ><span style="color: white; font-size: small"
                >Powerd by Ceylon Linux</span
              ></v-col
            >
          </v-row>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CryptoJS from "crypto-js";

export default {
  data: () => ({
    inputRules: [
      (v) => !!v || "Input is required",
      (v) => (v && v.length <= 50) || "Input must be less than 50 characters",
    ],
    show1: false,
    valid: true,
    axiosLoading: false,

    username: "",
    password: "",
  }),
  mounted() {
    localStorage.clear();
  },
  methods: {
    async submit() {
      this.valid = this.$refs.form.validate();

      if (this.valid) {
        this.axiosLoading = true;
        let apiPostData = {
          username: this.username,
          password: this.password,
        };
        await axios
          .post("/api/login", apiPostData)
          .then((response) => {
            if (response) {
              localStorage.setItem("token", response.data.token),
                localStorage.setItem(
                  "user",
                  CryptoJS.enc.Base64.stringify(
                    CryptoJS.enc.Utf8.parse(
                      response.data.user.user_group_id +
                        " " +
                        response.data.user.lname
                    )
                  )
                );
              localStorage.setItem(
                "userID",
                CryptoJS.enc.Base64.stringify(
                  CryptoJS.enc.Utf8.parse(response.data.user.u_id)
                )
              );
              localStorage.setItem(
                "userGroup",
                CryptoJS.enc.Base64.stringify(
                  CryptoJS.enc.Utf8.parse(response.data.user.user_group_id)
                )
              );
              localStorage.setItem(
                "initial",
                CryptoJS.enc.Base64.stringify(
                  CryptoJS.enc.Utf8.parse(response.data.user.initial_name)
                )
              );
              localStorage.setItem(
                "UserType",
                CryptoJS.enc.Base64.stringify(
                  CryptoJS.enc.Utf8.parse(this.getUserType(response.data.user))
                )
              );
              localStorage.setItem(
                "expiry",
                CryptoJS.enc.Base64.stringify(
                  CryptoJS.enc.Utf8.parse(
                    new Date().getTime() + 7 * 24 * 60 * 60 * 1000
                  )
                )
              );

              this.$router.push({ name: "HOME" });
            }
          })
          .then((this.axiosLoading = false));
      }
    },

    getUserType(userData) {
      let userType = "";

      if (userData.distributor_territory_ids) {
        userType = 4;
      } else if (userData.tso_territory_id || userData.tso_distributor_id) {
        userType = 5;
      } else if (userData.sm_sub_channel_id) {
        userType = 1;
      } else if (userData.tm_territory_ids) {
        userType = 2;
      } else if (userData.se_distributor_ids) {
        userType = 3;
      } else {
        userType = 6;
      }

      return userType;
    },
  },
};
</script>

<style scoped>
.login {
  width: 60%;
  margin: auto;
  margin-top: 100px;
  max-width: 500px;
}

.login .img {
  margin-left: auto;
  margin-right: auto;
}

.login .boxModel {
  margin-top: 50px;
  background-color: red;
  border-radius: 5px;
}

.login .FormModel {
  caret-color: white !important;
  padding-top: 50px;
  width: 80%;
  margin-left: auto;
  margin-right: auto;
}
</style>
