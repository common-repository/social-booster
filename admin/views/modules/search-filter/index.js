import Vue from 'vue';
Vue.component('SearchFilter', {
    template: '#rx-sb-module-search-filter',
    props: {
        authors: {
            type: Array
        },
        categories: {
            type: Array
        }
    },
    data() {
        return {
            keyword: '',
            authorsSelected: null,
            categoriesSelected: null,
            postTypeSelected: null,
            postType: [
              { value: null, text: 'All Post Types' },
              { value: 'post', text: 'Post' },
            ],
            orderSelected: 'new',
            orderType: [
              { value: 'new', text: 'Newest First' },
              { value: 'old', text: 'Oldest First' },
            ]
        };
    },
    methods: {
        searchFilter() {
            this.$emit('search-filter', {
                's'         : this.keyword,
                'author'    : this.authorsSelected,
                'cat'       : this.categoriesSelected,
                'post_type' : this.postTypeSelected,
                'order'     : this.orderSelected
            });
        }
    }
});
