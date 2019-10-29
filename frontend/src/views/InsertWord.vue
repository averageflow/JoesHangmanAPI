<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>{{ $t("insert_word_to_db") }}</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form>
              <v-text-field v-model="word" :label="$t('word')" type="text"></v-text-field>
              <v-select :items="languages" v-model="chosenLanguage" :label="$t('language')"></v-select>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="insertNewWord()">
              {{$t("insert")}}
            </v-btn>
          </v-card-actions>
        </v-card>
        <br />
        <v-alert v-if="successInserting === true" type="success">
          {{ $t("success_inserting") }}
        </v-alert>
        <br />
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
  name: "InsertWord",
  props: {
    source: String
  },
  data: () => ({
    word: "",
    languages: ["English", "Nederlands", "Català", "Português"],
    locales: { English: "en", Nederlands: "nl", Català: "ca", Português:"pt" },
    chosenLanguage: "English",
    netError: null,
    successInserting: null,
    currentStatus: null,
    currentUser: null
  }),
  mounted() {
    this.currentStatus = Boolean(this.$cookies.get("authenticated"));
    this.currentUser = String(this.$cookies.get("currentUser"));

    if (this.currentStatus === true) {
      //pass;
    } else {
      router.push({ name: "login" });
    }
  },

  methods: {
    insertNewWord() {
      if (this.password === this.c_password && this.password !== "") {
        var postData = {
          word: this.word.toUpperCase(),
          language: this.locales[this.chosenLanguage]
        };

        this.$http
          .post(this.$baseURI + "insert_word", postData, {
            headers: { Authorization: "Bearer " + this.$cookies.get("token") }
          })
          .then(res => {
            if (res.data["success"]) {
              this.successInserting = true;
              this.word = "";
            }
          })
          .catch(() => {
            this.netError = true;
          });
      } else {
        this.passError = true;
      }
    }
  }
};
</script>
