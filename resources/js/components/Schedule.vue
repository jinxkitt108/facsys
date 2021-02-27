<template>
<v-container>
     <v-tabs>
    <v-tab>Instructor</v-tab>
    <v-tab>Class</v-tab>
    <v-tab>Room</v-tab>
  </v-tabs>
</v-container>
</template>

<script>
export default {
  mounted() {
    console.log(this.$route.params.id);
  },

  created() {
    this.getFaculty();
  },

  data() {
    return {
      faculty: {}
    };
  },

  methods: {
    getFaculty() {
      let id = this.$route.params.id;
      axios.get("/api/faculty/" + id).then(response => {
        this.faculty = response.data[0];
      });
    }
  }
};
</script>
