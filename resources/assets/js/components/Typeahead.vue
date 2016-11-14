<template>
  <div style="position: relative;" v-bind:class="{'open': openDropdown}">
    <input
      type="text"
      class="form-control"
      v-model="queryString"
      @input="update"
      @keydown.up="up"
      @keydown.down="down"
      @keydown.enter= "hit"
      @keydown.esc="reset"
      :placeholder="placeholder"
      >
    <ul v-if="results.length" class="dropdown-menu" style="width: 100%">
      <li
        v-for="(result, index) in results"
        v-bind:class="{'active' : isActive(index)}"
        @mousemove="currentIndex = index"
        @mousedown.prevent="hit"
        ><a><partial :result="result" :templatePartial="templatePartial"></partial></a></li>
    </ul>
  </div>
</template>

<script>
  import _ from 'lodash'

  export default {
    props: {
      apiUri: {
        type: String,
        required: true
      },
      templatePartial: {
        type: String,
        required: true
      },
      successCallback: {},
      inputText: {
        type: String
      },
      placeholder: {
        type: String,
        default: ''
      }
    },  

    components: {
      partial: {
        template: '<div>{{ templatePartial }}</div>',

        props: {
          templatePartial: {
            type: String
          },
          result: {
            type: Object
          }
        },

        created() {
          this.$options.template = this.templatePartial

        }
      }
    },

    watch: {
      inputText (val) {
        this.queryString = val  
      }
    },

    data () {
      return {
        results: {},
        currentIndex: 0,
        openDropdown: false,
        queryString: '',
        loading: false,
      }
    },

    methods: {
      update: _.debounce(function () {
          const params = { q: this.queryString };
          this.$http.get(this.apiUri, { params })
            .then((response) => {
              this.results = response.body
              this.openDropdown = true
            })
        }, 500)
      ,
      up () {
        if (this.currentIndex > 0) {
          this.currentIndex -= 1
        }
      },
      down () {
        if (this.currentIndex < this.results.length) {
          this.currentIndex += 1 
        }
      },
      isActive (index) {
        return this.currentIndex === index
      },
      hit () {
        this.openDropdown = false
        this.successCallback(this.results[this.currentIndex])
      }
    }
  }
</script>

<style>
</style>