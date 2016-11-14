<template>
  <div class="container">
    <div class="col-md-10 col-md-offset-1">
      <p v-if="errorMessage">{{ errorMessage }}</p>
      <div v-for="image in images">
        <img :src="imageUrl(image[1])">
        <hr>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      id: {}
    },

    data() {
      return {
        images: false,
        errorMessage: false,
      }
    },

    created () {
      this.$http.get(`http://www.mangaeden.com/api/chapter/${this.id}/`)
        .then(({ body }) => {
          this.images = body.images;
        }, (response) => {
          this.errorMessage = '404 Page Not Found';
        })
    },

    methods: {
      imageUrl(url) {
        return `https://cdn.mangaeden.com/mangasimg/${url}`
      },
    },
  }
</script>