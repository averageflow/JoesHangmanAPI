<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>{{ $t("login_to_play") }}</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form>
              <v-text-field
                :label="$t('login')"
                v-model="login"
                type="text"
              ></v-text-field>

              <v-text-field
                id="password"
                :label="$t('password')"
                v-model="password"
                type="password"
              ></v-text-field>
              <v-select
                @change="changeAppLocale()"
                :items="languages"
                v-model="chosenLanguage"
                :label="$t('language')"
              ></v-select>
            </v-form>
            <p>{{ $t("lang_notice") }}</p>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="performLogin()">
              {{ $t("login") }}
            </v-btn>
          </v-card-actions>
        </v-card>
        <br />
        <v-alert v-if="justCreated === true" type="success">
          {{ t("login_new_account") }}
        </v-alert>
        <v-alert v-else type="info">{{ $t("login_tip") }}</v-alert>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import router from "../router";

export default {
  name: "LoginForm",
  props: {
    source: String,
    authenticated: Boolean,
    justCreatedAccount: Boolean
  },
  data: () => ({
    justCreated: false,
    locales: {},
    languages: [],
    chosenLanguage: "English",
    login: null,
    password: null
  }),
  mounted() {
    this.justCreated = this.$route.params["justCreatedAccount"];
    this.chosenLanguage = this.locales[this.$cookies.get("language")];
    this.locales = this.$myLocales;
    this.languages = this.$myLanguages;
  },
  methods: {
    changeAppLocale() {
      console.log(this.chosenLanguage);
      const currentChoice = this.$getKeyByValue(
        this.locales,
        this.chosenLanguage
      );
      console.log(currentChoice);
      this.$i18n.locale = currentChoice;
      this.$cookies.set("language", currentChoice);
    },
    performLogin() {
      const postData = {
        email: this.login,
        password: this.password
      };

      this.$http
        .post(this.$baseURI + "login", postData, {})
        .then(res => {
          if (res.data["success"] != null) {
            this.$cookies.set("authenticated", true);
            this.$cookies.set("currentUser", postData["email"]);
            this.$cookies.set("token", res.data["success"]["token"]);

            this.$emit("authenticatedChanged", true);
            this.$emit("userChanged", postData["email"]);

            router.push({
              name: "game",
              params: { authenticated: true, currentUser: postData["email"] }
            });
          }
        })
        .catch(() => {
          //pass;
        });
    }
  }
};
</script>
