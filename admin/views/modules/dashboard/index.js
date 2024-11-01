import Vue from 'vue';
import postModule from '../../store/modules/posts';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';

Vue.component('Dashboard', {
    template: '#rx-sb-module-dashboard',
    mixins: [ RegisterStoreModule ],
    created: function () {
        this.registerStoreModule('post', postModule);
    },
    computed: {
        ...mapGetters({
            latestPosts: 'post/latestPosts',
            allSchedulePosts: 'post/allSchedulePosts',
        }),
    },
    mounted() {
        this.$store.dispatch('post/getLatestPosts');
        this.$store.dispatch('post/getAllSchedulePosts');
    },
    data() {
        return {};
    }
});
