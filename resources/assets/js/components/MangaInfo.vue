<template>
  <div class="panel panel-default">
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
        <li v-for="chapter in manga.chapters">
          <span v-bind:class="[ dbManga.last_read_chapter < chapter[0] ? 'text-primary' : '']">
            Chapter {{ chapter[0] }} | {{ chapter[2] }}
            <span class="pull-right">
              <a @click="markChapterAsLastRead(chapter[0])" target="_blank" :href="chapter[3]">View</a> |
              <a @click="downloadChapter(chapter)" href="#">Download</a>
            </span>
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
      dbManga: {},
    },

    data() {
      return {
        manga: false,
        showChapters: false
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

      downloadChapter(chapter) {
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
              })
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