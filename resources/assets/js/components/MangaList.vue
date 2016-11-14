<template>
  <div class="panel panel-default">
    <div class="panel-heading"><h1 class="panel-title">Favorited Mangas</h1></div>
    <div class="panel-body">
      <ul>
        <li v-for="manga in mangas">{{ manga.title }}</li>
      </ul>
    </div>
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