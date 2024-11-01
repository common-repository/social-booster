import Vue from 'vue';
import postModule from '../../store/modules/posts';
import RegisterStoreModule from '@/utils/registerModule';
import { mapGetters } from 'vuex';
Vue.component('Settings', {
    template: '#rx-sb-module-settings',
    mixins: [ RegisterStoreModule ],
    created: function () {
      this.registerStoreModule('post', postModule);
    },
    data() {
        return {
          bitlyEnabler:false,
          login:'',
          apiKey:'',
          postTypesselected: [],
          postTypesOptions: [
            { text: 'None', value: 'none' },
          ]
        };
    },
    mounted: function () {
      this.$store.dispatch('post/bitlyData').then(r=>{
        if (r.bitly_enabler == 'true') {
          this.bitlyEnabler = true;
        }

        if (r.bitly_login) {
          this.login = r.bitly_login;
        }
        if (r.api_key) {
          this.apiKey = r.api_key;
        }
      });
      this.$store.dispatch('post/getPostTypeslist').then(r=>{
        this.postTypesOptions = r;
      });
      this.$store.dispatch('post/getPostTypesSelected').then(r=>{
        this.postTypesselected = r;
      });
    },
    methods: {
      saveBitlyData(evt) {
        this.$store.dispatch('post/savebitly',
            {
                'enabler' : this.bitlyEnabler,
                'login' :this.login,
                'api_key' : this.apiKey,
            }
        ).then(r=>{
          if (r == 'success') {
            this.$bvToast.toast('Successfully saved' , {
                title: 'Success',
                variant: 'success',
                autoHideDelay: 5000,
            })
          }
          else {
            this.$bvToast.toast('Error occured. Please disable other url shortener' , {
                title: 'Error',
                variant: 'error',
                autoHideDelay: 5000,
            })
          }
        });
      },
      savePostTypes(evt) {
        this.$store.dispatch('post/submitPostTypeslist',
            {
                'data' : this.postTypesselected,
            }
        ).then(r=>{
          if (r == 'success') {
            this.$bvToast.toast('Successfully saved' , {
                title: 'Success',
                variant: 'success',
                autoHideDelay: 5000,
            })
          }
          else {
            this.$bvToast.toast('Error occured.' , {
                title: 'Error',
                variant: 'error',
                autoHideDelay: 5000,
            })
          }
        });
      },
    },
});
