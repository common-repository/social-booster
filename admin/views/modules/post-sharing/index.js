import Vue from 'vue';
import postModule from '../../store/modules/posts';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';
Vue.component('PostSharing', {
    template: '#rx-sb-module-post-sharing',
    mixins: [ RegisterStoreModule ],
    created: function () {
        this.registerStoreModule('post', postModule);
    },
    computed: {
        ...mapGetters({
            allPosts: 'post/allPosts',
            allSchedulePosts: 'post/allSchedulePosts',
            authors: 'post/authors',
            categories: 'post/categories',
            totalPosts: 'post/totalPosts',
            instantSharedPosts: 'post/instantSharedPosts',
            sharedPosts: 'post/sharedPosts',
        }),
        rows() {
            return this.allPosts.length
        }
    },
    mounted() {
        this.$store.dispatch('post/getTotalPosts');
        this.$store.dispatch('post/getAllSchedulePosts');
        this.$store.dispatch('post/getAllPosts', {
            'offset' : this.offset
        });
        this.$store.dispatch('post/getAllAuthors');
        this.$store.dispatch('post/getAllCategories');
        this.$store.dispatch('post/getAllInstantSharedPosts');
        this.$store.dispatch('post/getAllSharedPosts');
    },
    data() {
        return {
            offset: 0,
            perPage: 10,
            currentPage: 1,
            authorsSelected: null,
            categoriesSelected: null,
            postTypeSelected: null,
            orderSelected: null,
            showPagination: true,
        };
    },
    methods: {
        searchFilter(filterParams) {
            this.showPagination = false;
            this.$store.dispatch('post/getAllPosts', {
                'offset' : 0,
                ...filterParams
            });
        },
        paginate(currentPage) {
            this.currentPage = currentPage;
            if(this.currentPage === 1) {
                this.offset = 0;
            }else {
                this.offset = ((this.currentPage - 1) * this.perPage );
            }
            this.$store.dispatch('post/getAllPosts', {
                'offset' : this.offset
            });
        }
    }
});
