import Vue from "vue";
import instantShareModule from "../../store/modules/instant_share";
import newtworkModule from '../../store/modules/networks';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from "vuex";
Vue.component("PinterestShare", {
  template: "#rx-sb-module-pinterest-share",
  mixins: [ RegisterStoreModule ],
  beforeCreate: function () {
      const store = this.$store;
      if (!(store && store.state && store.state['instantShareModule'])) {
          store.registerModule('instantShareModule', instantShareModule);

      }
      if (!(store && store.state && store.state['network'])) {
          store.registerModule('network', newtworkModule);

      }
  },
  props: {
    post: {
      type: Object
    },
    network: {
      type: Object
    }
  },
  data() {
    return {
      startShare: false,
      linkErr: "",
      search: ''
    };
  },
  computed: {

  },
  methods: {
    append(emoji) {
      this.post.title += emoji
    },
    shareNow: function() {
      this.startShare = true;
      if (!this.isUrl(this.post.url)) {
        this.linkErr = "Only URL are Allowed";
        this.startShare = false;
        return false;
      }
      this.$store.dispatch("instantShareModule/instantSharePt",
          {
            'post_id' : this.post.id,
            'network_id' : this.network.network_id,
            'id' : this.network.id,
            'status' : this.post.title,
            'link' : this.post.url,
          }
      ).then(r=>{
        this.startShare = false;
        if (r.success) {
          this.$bvToast.toast(`Successfully Shared` , {
              title: 'Success',
              variant: 'success',
              autoHideDelay: 5000,
          })
        }
        else {
          this.$bvToast.toast(r.error , {
              title: 'Error',
              variant: 'error',
              autoHideDelay: 5000,
          })
        }
      });
    },

    isUrl: function(url) {
      var re = /^(ftp|http|https):\/\/(([a-zA-Z0-9$\-_.+!*'(),;:&=]|%[0-9a-fA-F]{2})+@)?(((25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])){3})|localhost|([a-zA-Z0-9\-\u00C0-\u017F]+\.)+([a-zA-Z]{2,}))(:[0-9]+)?(\/(([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*(\/([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*)*)?(\?([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?(\#([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?)?$/;

      if (re.test(url)) {
        return true;
      } else {
        return false;
      }
    }
  },

  directives: {
    focus: {
      inserted(el) {
        el.focus()
      },
    },
  },
  
});
