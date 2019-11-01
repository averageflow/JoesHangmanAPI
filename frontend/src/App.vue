<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer" app clipped>
      <v-list dense>
        <v-list-item @click="goToGame()">
          <v-list-item-action>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ $t("game") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="goToLogin()">
          <v-list-item-action>
            <v-icon>mdi-account-circle</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ $t("login") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click.stop="goToRegister()">
          <v-list-item-action>
            <v-icon>mdi-account-heart</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ $t("register") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click.stop="goToInsertWord()">
          <v-list-item-action>
            <v-icon>mdi-currency-twd</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ $t("insert_word") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar app clipped-left>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>{{ $t("joes_hangman") }}</v-toolbar-title>
      <v-spacer></v-spacer>

      <v-btn icon @click.stop="switchMode()">
        <v-icon>mdi-lightbulb-on-outline</v-icon>
      </v-btn>
      <v-menu bottom left>
        <template v-slot:activator="{ on }">
          <v-btn
                  icon
                  v-on="on"
          >
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item
                  v-for="(item, i) in myColors"
                  :key="i"

          >
            <div :style="getColorCircle(item)"></div>
            <v-spacer></v-spacer>
            <v-list-item-title>{{ item }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <router-view
            @winsChanged="setWins"
            @lossesChanged="setLosses"
            @userChanged="setCurrentUser"
            @authenticatedChanged="setAuthenticated"
          ></router-view>
        </v-row>
      </v-container>
    </v-content>

    <v-footer app>
      <span>
        &copy; 2019 JJBA -
        <span v-if="currentStatus === true">
          {{ $t("logged_as") }}{{ this.currentUser }}&nbsp;|&nbsp;
          <span
            >{{ $t("wins") }}: {{ this.wins }} | {{ $t("losses") }}:
            {{ this.losses }}</span
          >
        </span>
        <span v-else>{{ $t("please_login") }}</span>
      </span>
    </v-footer>
  </v-app>
</template>
<style>
html {
  background: #303030;
}
</style>
<script>
import router from "./router";

export default {
  props: {
    source: String
  },

  data: () => ({
    drawer: null,
    currentStatus: false,
    currentUser: null,
    word: null,
    wins: 0,
    losses: 0,
    myColors: []
  }),

  created() {

    this.$cookies.config("30d");
    this.currentStatus = Boolean(this.$cookies.get("authenticated"));
    this.currentUser = String(this.$cookies.get("currentUser"));
    let lang = this.$cookies.get("language");
    if (lang) {
      this.$i18n.locale = lang;
    } else {
      this.$i18n.locale = "en";
      this.$cookies.set("language", "en");
    }
    const mode = this.$cookies.get("mode");
    this.enableMode(mode);
    this.myColors = this.$myColors;
  },
  methods: {
    goToGame() {
      router.push({
        name: "game",
        authenticated: this.$cookies.get("authenticated")
      });
    },
    getColorCircle(color){
      return (
        "background: " + color + "; border-radius:100%; height:20px;width:20px;"
      );
    },
    goToRegister() {
      router.push({ name: "register" });
    },
    goToLogin() {
      router.push({ name: "login" });
    },
    goToInsertWord() {
      router.push({
        name: "insert_word",
        authenticated: this.$cookies.get("authenticated")
      });
    },
    setAuthenticated(authenticated) {
      this.currentStatus = authenticated;
    },
    setCurrentUser(user) {
      this.currentUser = user;
    },
    setWins(wins) {
      this.wins = wins;
    },
    setLosses(losses) {
      this.losses = losses;
    },
    enableMode(mode) {
      if (mode) {
        this.$cookies.set("mode", mode);
        this.$vuetify.theme.dark = mode !== "light";
      } else {
        this.$cookies.set("mode", "dark");
        this.$vuetify.theme.dark = true;
      }
    },
    switchMode() {
      const mode = this.$cookies.get("mode");

      if (mode === "dark") {
        this.$vuetify.theme.dark = false;
        this.enableMode("light");
      } else {
        this.$vuetify.theme.dark = true;
        this.enableMode("dark");
      }
    },
    enableUserColor(){
      this.$vuetify.theme.themes.light.primary =
              this.$vuetify.theme.themes.dark.primary
    },
    switchUserColor(){

    }
  }
};
</script>
