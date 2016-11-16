<template>
  <div class="panel panel-default" v-if="isLoading">
    <div class="panel-body">
      <p class="text-center">Loading...</p>
      <clip-loader :loading="isLoading"></clip-loader>
    </div>
  </div>
  <div class="panel panel-default" v-else>
    <div class="panel-heading" v-if="manga">
      <div class="pull-right">
        <span
          v-bind:class="[ dbManga.favorited == '1' ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty' ]"
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
        <li v-for="(chapter, index) in manga.chapters">
          <span v-bind:class="[ dbManga.last_read_chapter < chapter[0] ? 'text-primary' : '']">
            Chapter {{ chapter[0] }} | {{ chapter[2] }}
            <span class="pull-right">
              <a @click="markChapterAsLastRead(chapter[0])" target="_blank" :href="chapter[3]">View</a> |
              <a @click="downloadChapter(index)" href="#">Download</a>
            </span>
          </span>
        </li>
      </ul>
    </div>
    <div class="panel-body" v-else><p class="text-center">Select a Manga</p></div>
  </div>
</template>

<script>
  import { ClipLoader } from 'vue-spinner/dist/vue-spinner.min.js';

  export default {
    props: {
      dbManga: {},
    },

    components: {
      ClipLoader,
    },

    data() {
      return {
        manga: false,
        showChapters: false,
        isLoading: false,
        downloadingChapters: [],
      };
    },

    methods: {
      loadManga(id) {
        this.isLoading = true;
        this.$http.get(`http://www.mangaeden.com/api/manga/${id}/`)
          .then(({ body }) => {
            this.manga = body;
          }).finally(() => {
            this.isLoading = false;
          })
      },

      toggleFavorite() {
        this.$http.post(`api/mangas/${this.dbManga.id}/toggleFavorite`)
          .then(({ body }) => {
            this.dbManga = body;
            this.$bus.$emit('refresh-manga-list');
          });
      },

      markChapterAsLastRead(chapter) {
        const payLoad = { last_read_chapter: chapter }
        this.$http.patch(`api/mangas/${this.dbManga.id}/lastReadChapter`, payLoad)
          .then(({ body }) => {
            this.dbManga = body;
          });
      },

      downloadChapter(index) {
        this.manga.chapters[index][4] = true;
        const chapter = this.manga.chapters[index];
        this.$http.get(`api/chapters/${chapter[3]}/images?chapter=${chapter[0]}&manga=${this.dbManga.title}`)
          .then(({ body }) => {
            const payLoad = {
              chapter: chapter[0],
              manga: this.dbManga.title,
              imageFilenames: body
            }
            this.$http.post(`api/chapters/${chapter[3]}/zip`, payLoad)
              .then((res) => {
                const fileName = res.body;
                window.location.href = `/uploads/${fileName}`;
                this.markChapterAsLastRead(chapter[0]);
              }).finally(() => {
                this.manga.chapters[index][4] = false;
              });
          })
      },
    },
    computed: {
      mangaImage() {
        return `http://cdn.mangaeden.com/mangasimg/${this.manga.image}`;
      },
    },

    watch: {
      'dbManga.id'(val) {
        this.loadManga(val);
      },
    },
  }
</script>