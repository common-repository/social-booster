import Vue from 'vue';
import newtworkModule from '../../store/modules/networks';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';
Vue.component('Switcher', {
    template: '#rx-sb-module-switcher',
    mixins: [ RegisterStoreModule ],
    created: function () {
        this.registerStoreModule('network', newtworkModule);
    },
    props:['switcherId', 'isChecked' , 'dataId', 'dataCheck'],
    data() {
        return {

        };
    },
    computed: {
       ...mapGetters({
           profiles: 'network/profiles',
           currentProfile: 'network/currentProfile',
       }),
   },
    methods: {
        statusOnchange(value) {
          let _this = this;
          var getid = value.id;
          var idarray = getid.split("-");
          var id = idarray[1];
          var checked = value.checked;
          this.$store.dispatch('network/editNetworkStatus', {
              'id' : id,
              'checked' : checked,
          });
        },
    },
});
