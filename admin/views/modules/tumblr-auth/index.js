import Vue from 'vue';
import axios from 'axios';
import newtworkModule from '../../store/modules/networks';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';
Vue.component('TumblrAuth', {
    template: '#rx-sb-module-tumblr-auth',
    mixins: [ RegisterStoreModule ],
    created: function () {
        this.registerStoreModule('network', newtworkModule);
    },
    data() {
        return {
            consumer_key: "",
            consumer_secret: "",
            blog_url: "",
            authStart: false,
            site_protocol:'',
            site_hostname:'',
            site_url:'',
            site_callback:'',
            copyMsg: 'Click to copy',
            currentHoverEl : ''
        }
    },

    computed: {
        ...mapGetters({
            currentProfile: 'network/currentProfile',
        }),
    },

    methods: {
        sub: function(event){
            event.preventDefault();
            this.authStart = true;
            axios.post(window.rx_sb_obj.api_url+'/addTbAuth', {
                blog_url: this.blog_url,
                consumer_key: this.consumer_key,
                consumer_secret: this.consumer_secret,
                profile_id: this.currentProfile.profile_id,
                profile_name: this.currentProfile.profile_name,
            })
                .then(function (response) {
                    window.location = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        mouseLeave: function () {
            this.copyMsg = 'Click to copy';
            this.$root.$emit('bv::hide::tooltip')
        },

        clickToCopy: function (event) {
            event.preventDefault();
            let clickedElement = event.currentTarget.id;
            let copyTextEl = '';
            switch(clickedElement) {
                case 'click-copy-host':
                    copyTextEl = document.querySelector('#copied-host');
                    break;
                case 'click-copy-url':
                    copyTextEl = document.querySelector('#copied-url');
                    break;
                case 'click-copy-call-url':
                    copyTextEl = document.querySelector('#copied-call-url');
                    break;
                case 'click-copy-url-tb':
                    copyTextEl = document.querySelector('#copied-url');
                    break;
                case 'click-copy-call-url-tb':
                    copyTextEl = document.querySelector('#copied-call-url');
                    break;
                default:
                    copyTextEl = document.querySelector('#copied-host');
                    break;
            }
            copyTextEl.setAttribute('type', 'text');
            copyTextEl.select();
            try {
                document.execCommand('copy');
                this.copyMsg = 'Copied';
            } catch (err) {
                this.copyMsg = 'Click to copy';
            }
            copyTextEl.setAttribute('type', 'hidden');
            window.getSelection().removeAllRanges();
        }
    },
    mounted(){
        this.site_protocol = location.protocol;
        this.site_hostname = window.location.hostname;
        this.site_url = document.location.origin;
        this.site_callback = document.location.origin + '/wp-admin/';
    }
});
