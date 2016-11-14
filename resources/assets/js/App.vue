<template>
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading"><h1 class="panel-title">Manga Laravel</h1></div>
      <div class="panel-body">
        <manga-list></manga-list>
      </div>
    </div>
  </div>
</template>

<script>
  import MangaList from './components/MangaList.vue';

  export default {
    components: {
      MangaList
    },
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