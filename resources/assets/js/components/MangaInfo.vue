<template>
  <div class="panel panel-default">
    <div class="panel-heading" v-if="manga">
      <div class="pull-right">
        <span 
          v-bind:class="[ isFavorited == '1' ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty' ]"
          @click="toggleFavorite"
          ></span>
      </div>
      <h1 class="panel-title">
        {{ manga.title }}
      </h1>
      <h6>
        {{ manga.author }} | {{ manga.artist }}
      </h6>
    </div>
    <div class="panel-body" v-if="manga">
      <img :src="mangaImage" class="pull-right" v-if="manga">
      <p>{{ manga.description }}</p>
      <span @click="showChapters = !showChapters" style="cursor: pointer;">
        <u>Chapters {{ showChapters ? '&#9650;' : '&#9660;'}}</u>
      </span>
      <ul v-if="showChapters">
        <li v-for="chapter in manga.chapters">
          Chapter {{ chapter[0] }} | {{ chapter[2] }}
          <span class="pull-right">
            <a :href="chapter[3]">View</a>
          </span>
        </li>
      </ul>
    </div>
    <div class="panel-body" v-else><p class="text-center">Select a Manga</p></div>
  </div>
</template>

<script>
  export default {
    props: {
      id: {
        type: String
      },
      favorited: 0,
    },

    data() {
      return {
        manga: false,
        showChapters: false,
        isFavorited: this.favorited,
      };
    },

    methods: {
      loadManga(id) {
        this.$http.get(`http://www.mangaeden.com/api/manga/${id}/`)
          .then(({ body }) => {
            this.manga = body;
          })
      },

      toggleFavorite() {
        this.$http.post(`api/mangas/${this.id}/toggleFavorite`)
          .then(({ body }) => {
            this.isFavorited = body.favorited;
            this.$bus.$emit('refresh-manga-list');
          });
      },
    },

    computed: {
      mangaImage() {
        return `http://cdn.mangaeden.com/mangasimg/${this.manga.image}`;
      },
    },

    watch: {
      id(val) {
        this.isFavorited = this.favorited;
        this.loadManga(val);
      },
    },
  }
</script>