<template>
  <div>
    <h3>Favorited Mangas</h3>
    <ul>
      <li v-for="manga in mangas">{{ manga.title }}</li>
    </ul>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        mangas: {},
      };
    },

    created() {
      this.$http.get('api/mangas?favorited=1&sort=title')
        .then(({ body }) => {
          this.mangas = body;
        })
    }
  }
</script>