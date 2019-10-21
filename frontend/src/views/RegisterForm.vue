<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>{{ $t("make_an_account") }}</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form>
              <v-text-field
                v-model="name"
                :label="$t('name')"
                type="text"
              ></v-text-field>
              <v-text-field
                v-model="email"
                :label="$t('email')"
                type="text"
              ></v-text-field>
              <v-text-field
                v-model="password"
                :label="$t('password')"
                type="password"
              ></v-text-field>
              <v-text-field
                v-model="c_password"
                :label="$t('confirm_password')"
                type="password"
              ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="registerUser()">{{
              $t("register")
            }}</v-btn>
          </v-card-actions>
        </v-card>
        <br />
        <v-alert v-if="netError" type="warning">{{
          $t("error_connecting")
        }}</v-alert>
        <br />
        <v-alert v-if="passError" type="warning">{{
          $t("password_error")
        }}</v-alert>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import router from "../router";
export default {
  name: "RegisterForm",
  props: {
    source: String
  },
  data: () => ({
    name: "",
    email: "",
    password: "",
    c_password: "",
    passError: false,
    netError: false
  }),
  methods: {
    registerUser() {
      if (this.password === this.c_password && this.password !== "") {
        var postData = {
          name: this.name,
          email: this.email,
          password: this.password,
          c_password: this.c_password
        };
        let axiosConfig = {};

        this.$http
          .post(this.$baseURI + "register", postData, axiosConfig)
          .then(res => {
            //console.log("RESPONSE RECEIVED: ", res);
            if (res.data["success"] != null) {
              router.push({
                name: "login",
                props: { justCreatedAccount: true }
              });
            }
          })
          .catch(() => {
            //console.log("AXIOS ERROR: ", err);
            this.netError = true;
          });
      } else {
        this.passError = true;
      }
    }
  }
};
</script>
