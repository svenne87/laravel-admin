<template>
  <div class="filter-bar mb-4 float-right">
    <div class="form-inline">
      <div class="form-group">
        <input type="text" v-model="filterText" class="form-control" @keyup.enter="doFilter" :placeholder="$t('general.search_for')">
        <button class="btn btn-primary" @click="doFilter">{{ $t('general.search') }}</button>
        <!--<button class="btn btn-default" @click="resetFilter">Reset</button>-->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      filterText: ''
    }
  },
  methods: {
    doFilter () {
      this.$events.fire('filter-set', this.filterText)
    },
    resetFilter () {
      this.filterText = ''  // clear the text in text input
      this.$events.fire('filter-reset')
    }
  }
}
</script>