<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="10" md="8">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-btn color="secondary">{{ $t("lives") }} {{ this.lives }}</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="secondary" @click="getRandomWord()">
              {{ $t("new_word") }}
            </v-btn>
          </v-toolbar>
          <v-card-text style="text-align:center;">
            <h3 style="color:red;letter-spacing:0.6em;">
              {{ this.blacklist }}
            </h3>
            <img width="50%" v-bind:src="hangmanImage" alt="Hangman Image" />
            <h3 style="letter-spacing:0.6em;">{{ this.currentWord }}</h3>
          </v-card-text>
          <v-card-actions>
            <v-row>
              <v-col cols="12" md="6" sm="12">
                <v-text-field
                  v-model="desiredLetter"
                  :maxlength="1"
                  v-on:keyup.enter="guessLetter()"
                  :label="$t('choose_a_letter')"
                  type="text"
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6" sm="12">
                <v-btn color="primary" @click="guessLetter()">
                  {{ $t("submit") }}
                </v-btn>
              </v-col>
            </v-row>
          </v-card-actions>
        </v-card>
        <v-alert v-if="victory === true" type="success">
          {{ $t("victory_message") }}
        </v-alert>
        <v-alert v-if="victory === false" type="error">
          {{ $t("lost_message") }}
        </v-alert>
        <v-alert v-if="letterError === true" type="error">
          {{ $t("blacklist_letter_message") }}
        </v-alert>
        <v-alert v-if="netError === true" type="error">
          {{ $t("network_error") }}
        </v-alert>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import router from "../router";

export default {
  name: "Game",

  created() {
    this.currentStatus = Boolean(this.$cookies.get("authenticated"));
    this.currentUser = String(this.$cookies.get("currentUser"));
    this.myToken = String(this.$cookies.get("token"));

    if (this.currentStatus === true) {
      this.getCurrentWord();
      this.getUserScore();
    } else {
      router.push({ name: "login" });
    }
  },
  data: () => ({
    currentWord: null,
    blacklist: null,
    desiredLetter: null,
    victory: null,
    lives: 12,
    authenticated: null,
    currentUser: null,
    letterError: null,
    hangmanImage: null,
    netError: null
  }),

  methods: {
    getHangmanImage() {
      this.$http
        .post(
          this.$baseURI + "get_hangman",
          { lives: this.lives },
          { headers: { Authorization: "Bearer " + this.myToken } }
        )
        .then(res => {
          this.netError = false;
          if (res.data["hangman"] != null) {
            this.hangmanImage = res.data["hangman"];
          }
        })
        .catch(() => {
          this.netError = true;
        });
    },
    guessLetter() {
      if (!this.desiredLetter || this.desiredLetter === " ") {
        return;
      }
      if (this.blacklist != null) {
        let myForbiddenLetters = this.blacklist.split(" ");
        if (myForbiddenLetters.includes(this.desiredLetter.toUpperCase())) {
          this.letterError = true;
          this.desiredLetter = null;
          return;
        }
      }

      this.letterError = false;
      let postData = {
        letter: this.desiredLetter,
        user: this.currentUser
      };

      this.$http
        .post(this.$baseURI + "guess_letter", postData, {
          headers: { Authorization: "Bearer " + this.myToken }
        })
        .then(res => {
          this.netError = false;
          if (res.data["victory"] != null) {
            this.victory = res.data["victory"];

            if (this.victory === true) {
              this.setUserScore("won");
              this.getUserScore();
            } else {
              this.setUserScore("lost");
              this.getUserScore();
            }
          }
          this.lives = res.data["lives"];
          this.currentWord = res.data["currentWord"];
          this.blacklist = res.data["blacklist"];
          this.getHangmanImage();
        })
        .catch(() => {
          this.netError = true;
        });

      this.desiredLetter = null;
    },

    getUserScore() {
      let postData = {
        user: this.currentUser
      };

      this.$http
        .post(this.$baseURI + "get_score", postData, {
          headers: { Authorization: "Bearer " + this.myToken }
        })
        .then(res => {
          this.netError = false;
          if (res.data["wins"] && res.data["losses"]) {
            this.$emit("winsChanged", res.data["wins"]);
            this.$emit("lossesChanged", res.data["losses"]);
          } else if (res.data["error"] != null) {
            alert(res.data["error"]);
          }
        })
        .catch(() => {
          this.netError = true;
        });
    },
    setUserScore(status) {
      let postData = {
        outcome: status,
        user: this.currentUser
      };

      this.$http
        .post(this.$baseURI + "set_score", postData, {
          headers: { Authorization: "Bearer " + this.myToken }
        })
        .then(res => {
          this.netError = false;
          if (res.data["success"]) {
            //pass;
          } else if (res.data["error"]) {
            alert(res.data["error"]);
          }
        })
        .catch(() => {
          this.netError = true;
        });
    },

    getCurrentWord() {
      let postData = {
        newWord: false,
        user: this.currentUser
      };

      this.$http
        .post(this.$baseURI + "get_current_word", postData, {
          headers: { Authorization: "Bearer " + this.myToken }
        })
        .then(res => {
          this.netError = false;
          if (res.data["word"]) {
            this.currentWord = res.data["word"];
            this.lives = res.data["lives"];
            this.blacklist = res.data["blacklist"];
            this.getHangmanImage();
          } else if (res.data["status"] === "No records available!") {
            this.getRandomWord();
          } else if (res.data["error"]) {
            alert(res.data["error"]);
          }
        })
        .catch(() => {
          this.netError = true;
        });
    },

    getRandomWord() {
      this.victory = null;
      this.lives = 12;
      this.blacklist = null;
      this.desiredLetter = null;
      this.letterError = null;
      this.hangmanImage = null;

      let postData = {
        newWord: true,
        user: this.currentUser,
        language: this.$cookies.get("language")
      };

      this.$http
        .post(this.$baseURI + "get_word", postData, {
          headers: { Authorization: "Bearer " + this.myToken }
        })
        .then(res => {
          this.netError = false;
          if (res.data["word"]) {
            this.currentWord = res.data["word"];
          } else if (res.data["error"]) {
            alert(res.data["error"]);
          }
        })
        .catch(() => {
          this.netError = true;
        });
    }
  }
};
</script>
