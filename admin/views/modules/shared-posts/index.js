import Vue from "vue";
import postModule from '../../store/modules/posts';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';
import moment from 'moment';
Vue.component("SharedPosts", {
    name: 'SharedPosts',
    mixins: [ RegisterStoreModule ],
    template: '#rx-sb-module-shared-posts',
    created: function () {
        this.registerStoreModule('post', postModule);
    },
    props: {
        sharedPosts: {
            type: Object,
        },
        postType: {
            type: String,
        }
    },
    data() {
        return {
            currentScheduleData: {},
            showScheduleModal: false,
            caption: ''
        };
    },
    filters: {
        momentDate: function (date) {
            return moment(date).format('YYYY-MM-DD');
        },
        momentTime: function (date) {
            return moment(date).format('YYYY-MM-DD h:mm A');
        }
    },
    computed: {
        ...mapGetters({
            toastMessage: 'post/toastMessage',
        }),

    },

    methods: {
        showEditModal(schedule) {
            this.currentScheduleData = schedule;
            this.showScheduleModal = true;
        },
        hideModal() {
            this.showScheduleModal = false;
        },
        confirmDelete(id) {
          this.$swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              this.$store.dispatch('post/deleteSchedule', {'id':id});
              this.$bvToast.toast('Success' , {
                  title: 'Success',
                  variant: 'success',
                  autoHideDelay: 5000,
              })
            }
          });
        },
    },
});
