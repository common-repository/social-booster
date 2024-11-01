import Vue from 'vue';
import moment from 'moment';
Vue.component('RecentPosts', {
    template: '#rx-sb-module-recent-posts',
    props: {
        latestPosts: {
            type: Array,
        },
    },
    filters: {
        momentDate: function (date) {
            return moment(date).format('YYYY-MM-DD');
        },
        momentTime: function (date) {
            return moment(date).format('h:mm A');
        }
    },
    data() {
        return {
            currentPost: {},
            showPostModal: false,
            showschedulepost: false
        };
    },
    methods: {
        showShareModal(post) {
            this.currentPost = post;
            this.showPostModal = true;
        },
        showscheduleModal(post) {
            this.currentPost = post;
            this.showschedulepost = true;
        },
        hideModal() {
            this.showPostModal = false;
            this.showschedulepost = false;
        }
    },
});
