<template>
  <div class="panel panel-default">
    <div class="panel-heading"><h1 class="panel-title">Favorited Mangas</h1></div>
    <div class="panel-body">
      <ul>
        <li v-for="manga in mangas" @click="selectManga(manga)">
          <span style="cursor: pointer;"><u>{{ manga.title }}</u></span>
        </li>
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
      this.$bus.$on('refresh-manga-list', () => {
        this.fetchData();
      });
      this.fetchData();
    },

    methods: {
      selectManga(manga) {
        this.$bus.$emit('select-manga', manga);
      },

      fetchData() {
        this.$http.get('api/mangas?favorited=1&sort=title')
          .then(({ body }) => {
            this.mangas = body;
          })
      }
    },
  }
</script>