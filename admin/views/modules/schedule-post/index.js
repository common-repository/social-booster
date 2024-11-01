import Vue from 'vue';
import datetime from 'vuejs-datetimepicker';
import postModule from '../../store/modules/posts';
import newtworkModule from '../../store/modules/networks';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';

Vue.component('SchedulePost', {
    template: '#rx-sb-module-schedule-post',
    mixins: [ RegisterStoreModule ],
    components: {
      datetime
    },
    created: function () {
      this.registerStoreModule('post', postModule);
      this.registerStoreModule('network', newtworkModule);
    },
    props: {
        post: {
            type: Object
        },
        showModal: {
            type: Boolean
        }
    },
    data() {
        return {
            caption: '',
            scheduleData: {},
            schedule_type: 'none',
            options: [
                { value: 'none', text: 'No recurring' },
                { value: 'daily', text: 'Daily' },
                { value: 'weekly', text: 'Weekly'},
                { value: 'monthly', text: 'Monthly' },
            ],
            media:[],
            errorReport:''
        };
    },

    computed: {
        ...mapGetters({
            currentProfile: 'network/profiles',
        }),
        modalShow: {
            get: function() {
                return this.showModal;
            },

            set: function(newValue) {
                this.initialState();
                this.$emit('hide-Modal');
            }
        },
    },
    mounted: function () {
        this.$store.dispatch('network/getAllProfiles');
        this.$store.dispatch('post/getAllSchedulePosts');
    },
    methods: {
        hideModal(bvModalEvt) {
            this.initialState();
            bvModalEvt.preventDefault();
            this.$emit('hide-Modal');
        },
        initialState (){
          this.caption = '';
          this.scheduleData= {};
          this.schedule_type= 'none';
          this.media=[];
          this.errorReport='';
        },
        addSchedule(evt) {
          this.$store.dispatch('post/addSchedule',
                {
                    'media' : this.media,
                    'schedule' :this.schedule_type,
                    'caption' : this.caption,
                    'post' : this.post.id,
                    'date' : this.scheduleData.date,
                    'time' : this.scheduleData.time,
                }
            ).then(r=>{
              this.$store.dispatch('post/getAllSchedulePosts');
              if (r.status == 'error') {
                this.$bvToast.toast(r.message , {
                    title: 'Error',
                    variant: 'error',
                    autoHideDelay: 5000,
                })
              }
              else {
                this.initialState();
                this.$emit('hide-Modal');
                this.$bvToast.toast(r.message , {
                    title: 'Success',
                    variant: 'success',
                    autoHideDelay: 5000,
                })
              }
            });
        },
    },
});
