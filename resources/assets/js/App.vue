<template>
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading"><h1 class="panel-title">Manga Laravel</h1></div>
      <div class="panel-body">
        <typeahead
          api-uri="/api/mangas?limit=10&"
          :template-partial="templatePartial"
          :success-callback="selectManga"
          :input-text="inputText"
        />
        <manga-list></manga-list>
      </div>
    </div>
  </div>
</template>

<script>
  import MangaList from './components/MangaList.vue';
  import Typeahead from './components/Typeahead.vue';

  export default {
    components: {
      MangaList, Typeahead,
    },

    data() {
      return {
        selectedManga: false,
        inputText: '',
        templatePartial: '<span>{{ result.title }}</span>',
      }
    },

    methods: {
      selectManga(manga) {
        this.selectedManga = manga;
        this.inputText = manga.title;
      }
    }
  }
</script>

<style lang="css">
  .container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
  }
</style>