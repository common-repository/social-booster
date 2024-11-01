import Vue from 'vue';
import moment from 'moment';
import newtworkModule from '../../store/modules/networks';
import postModule from '../../store/modules/posts';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';
import JQuery from 'jquery'
let $ = JQuery
Vue.component('Networks', {
    template: '#rx-sb-module-networks',
    mixins: [ RegisterStoreModule ],
    created: function () {
        this.registerStoreModule('network', newtworkModule);
        this.registerStoreModule('post', postModule);
    },
    filters: {
      momentDate: function (date) {
          return moment(date).format('YYYY-MM-DD');
      },
      momentTime: function (date) {
          return moment(date).format('h:mm:ss');
      }
    },
    data() {
        return {
            component: null,
            isAuthWindowShow: false,
            activeNetworkText: 'Connect a network',
            authPlatform: 'profile',
            spinner: false
        }
    },

    computed: {
        ...mapGetters({
            profiles: 'network/profiles',
            currentProfile: 'network/currentProfile',
            toastMessage: 'network/toastMessage',
            activeTab: 'network/activeTab'
        }),

    },
    watch: {
        toastMessage(newValue, oldValue) {
            if(newValue.type === 'success') {
                this.$bvToast.toast(`${newValue.message}` , {
                    title: 'Success',
                    variant: 'success',
                    autoHideDelay: 5000,
                })
            }else if(newValue.type === 'error') {
                this.$bvToast.toast(`Something gonna wrong` , {
                    title: 'Error',
                    variant: 'error',
                    autoHideDelay: 5000,
                })
            }
        },
    },
    methods: {
        openNewTab() {
            let profile_id = 'rx_' + Math.random().toString(36).substr(-7);
            this.$store.commit('network/ACTIVE_PROFILE', profile_id);
            let newTab = {
                'profile_name' : 'My Profile',
                'profile_id' : profile_id,
                'networks' : [],
            };

            this.$store.commit('network/SET_NEW_PROFILE', {
                [profile_id] : newTab
            });

            if (profile_id) {
              this.spinner = true;
              this.$store.dispatch('network/createProfile',
                {
                    'profile_id' : profile_id,
                    'profile_name' : 'My Profile',
                }
              ).then(r=>{
                this.spinner = false;
                this.$store.commit('network/ACTIVE_PROFILE', profile_id);
                if (r.success) {
                  this.$bvToast.toast('Saved' , {
                      title: 'Success',
                      variant: 'success',
                      autoHideDelay: 5000,
                  })
                }
                else {
                  this.$bvToast.toast('Failed' , {
                      title: 'Error',
                      variant: 'error',
                      autoHideDelay: 5000,
                  })
                }
              });
            }
        },

        activeProfile(profileID) {
            this.$store.commit('network/ACTIVE_PROFILE', profileID);
        },

        deleteProfile(id) {
          this.$swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              this.spinner = true;
              this.$store.dispatch('network/deleteProfile',
                {
                    'id' : id
                }
              ).then(r=>{
                this.spinner = false;
                this.$store.dispatch('network/getAllProfiles');
                this.$store.dispatch('post/getAllSchedulePosts');
                this.$store.dispatch('post/getAllSharedPosts');
                this.$store.dispatch('post/getAllInstantSharedPosts');
                this.$store.commit('network/ACTIVE_PROFILE', id);
                if (r.status == 'error') {
                  this.$bvToast.toast(r.message , {
                      title: 'Error',
                      variant: 'error',
                      autoHideDelay: 5000,
                  })
                }
                else {
                  this.$bvToast.toast(r.message , {
                      title: 'Success',
                      variant: 'success',
                      autoHideDelay: 5000,
                  })
                }
              });
            }
          });
        },
        saveProfile(){
            this.spinner = true;
            this.$store.dispatch('network/createProfile',
              {
                  'profile_id' : this.currentProfile.profile_id,
                  'profile_name' : this.currentProfile.profile_name,
              }
            ).then(r=>{
              this.spinner = false;
              this.$store.commit('network/ACTIVE_PROFILE', this.currentProfile.profile_id);
              if (r.success) {
                this.$bvToast.toast('Saved' , {
                    title: 'Success',
                    variant: 'success',
                    autoHideDelay: 5000,
                })
              }
              else {
                this.$bvToast.toast('Failed' , {
                    title: 'Error',
                    variant: 'error',
                    autoHideDelay: 5000,
                })
              }
            });

        },
        showNetworkAuth(component, text, platform) {
            this.isAuthWindowShow = true;
            this.component = component;
            this.activeNetworkText = text;
            this.authPlatform = platform;
        },
        backToProfile() {
            this.component = null;
            this.isAuthWindowShow = false;
            this.activeNetworkText = 'Connect a network';
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
              this.$store.dispatch('network/deleteNetwork', {'id':id});
              this.$store.dispatch('post/getAllSharedPosts');
              this.$store.dispatch('post/getAllInstantSharedPosts');
              this.$store.dispatch('post/getAllSchedulePosts');
            }
          });
        },
    },
    mounted: function () {
        this.$store.dispatch('network/getAllProfiles');
        $("body").on("click", function(){
            $(".single-network .network-type-wrapper").hide();
        });
        $(document).on('click', '.single-network', function (e) {
            e.stopPropagation();
            $(this).children(".network-type-wrapper").toggle();
            $(this).siblings().children(".network-type-wrapper").hide();
        });
    },
});
