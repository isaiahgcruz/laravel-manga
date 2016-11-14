<template>
  <div class="panel panel-default">
    <div class="panel-heading" v-if="manga">
      <h1 class="panel-title">
        {{ manga.title }} || {{ manga.artist }} {{ manga.author}}
      </h1>
    </div>
    <div class="panel-body" v-if="manga">
      <img :src="mangaImage" class="pull-right" v-if="manga">
      <p>{{ manga.description }}</p>
      <span @click="showChapters = !showChapters">Chapters</span>
      <ul v-if="showChapters">
        <li v-for="chapter in manga.chapters">Chapter {{ chapter[0] }} | {{ chapter[2] }}</li>
      </ul>
    </div>
    <div class="panel-body" v-else>Select a Manga</div>
  </div>
</template>

<script>
  export default {
    props: {
      id: {
        type: String
      }
    },

    data() {
      return {
        manga: false,
        showChapters: false,
      };
    },

    methods: {
      loadManga(id) {
        this.$http.get(`http://www.mangaeden.com/api/manga/${id}/`)
          .then(({ body }) => {
            this.manga = body;
          })
      }
    },

    computed: {
      mangaImage() {
        return `http://cdn.mangaeden.com/mangasimg/${this.manga.image}`;
      },
    },

    watch: {
      id(val) {
        this.loadManga(val);
        console.log(this.mangaImage);
      },
    },
  }
</script>