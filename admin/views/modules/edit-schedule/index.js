import Vue from 'vue';
import datetime from 'vuejs-datetimepicker';
import postModule from '../../store/modules/posts';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';
Vue.component('EditSchedule', {
    template: '#rx-sb-module-edit-schedule',
    mixins: [ RegisterStoreModule ],
    components: {
      datetime
    },
    created: function () {
        this.registerStoreModule('post', postModule);
    },
    props: {
        scheduleData: {
            type: Object
        },
        showModal: {
            type: Boolean
        }
    },
    data() {
        return {
            options: [
                { value: 'none', text: 'No recurring' },
                { value: 'daily', text: 'Daily' },
                { value: 'weekly', text: 'Weekly'},
                { value: 'monthly', text: 'Monthly' },
            ]
        };
    },

    computed: {

        ...mapGetters({
            startUpdate: 'post/startUpdate',
            updateCompleteMessage: 'post/updateCompleteMessage',
        }),
        modalShow: {
            get: function() {
                return this.showModal;
            },

            set: function(newValue) {
                this.$emit('hide-Modal');
            }
        },
        captionText: {
            get: function() {
                return this.caption;
            },

            set: function(newValue) {
                this.caption = newValue;
            }
        }
    },
    methods: {
        hideModal(bvModalEvt) {
            bvModalEvt.preventDefault();
            this.$emit('hide-Modal');
        },
        updateSchedule(evt) {
          this.$store.dispatch('post/editSchedule',
              {
                  'id' : this.scheduleData.id,
                  'schedule' :this.scheduleData.schedule_type,
                  'caption' : this.scheduleData.caption,
                  'date' : this.scheduleData.date,
                  'time' : this.scheduleData.time,
              }
          ).then(r=>{
              this.$bvToast.toast(`Schedule updated` , {
                  title: 'Success',
                  variant: 'success',
                  autoHideDelay: 5000,
              })
          });

        }
    },
});
