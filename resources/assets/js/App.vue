<template>
  <div class="container">
    App
    <manga-list></manga-list>
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