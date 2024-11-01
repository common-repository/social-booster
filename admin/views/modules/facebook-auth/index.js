import Vue from 'vue';
import axios from 'axios';
import newtworkModule from '../../store/modules/networks';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';
Vue.component('FacebookAuth', {
    template: '#rx-sb-module-facebook-auth',
    mixins: [ RegisterStoreModule ],
    created: function () {
        this.registerStoreModule('network', newtworkModule);
    },
    props: {
        authPlatform: String,
    },
    data() {
        return {
            applicant_id: "",
            applicant_secret: "",
            postFormat: 'Simple text message',
            authPlatformId: '',
            authStart: false,
            authdata:[
                {
                    app_id:'',
                    app_secret:'',
                    auth_platform:'',
                    auth_platform_id:'',
                    accessToken:'',
                },
            ],
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
            this.auth();
            this.authStart = true;
            this.authdata.app_id = this.applicant_id;
            this.authdata.app_secret = this.applicant_secret;
            this.authdata.auth_platform = this.authPlatform;
            this.authdata.auth_platform_id = this.authPlatformId;
        },

        auth: function(event){
            let _this = this;
            FB.init({
                appId: this.applicant_id,
                cookie: true,
                version: 'v3.3'
            });
            FB.getLoginStatus(function(response){

            });

            FB.login(function(response) {
                if (response.authResponse) {
                    _this.authdata.accesstoken = response.authResponse.accessToken;
                    if(_this.authdata.accesstoken) {
                        axios.post(window.rx_sb_obj.api_url+'/addFbAuth', {
                            app_id: _this.authdata.app_id,
                            app_secret: _this.authdata.app_secret,
                            platform: _this.authdata.auth_platform,
                            platform_id: _this.authdata.auth_platform_id,
                            accesstoken: _this.authdata.accesstoken,
                            profile_id: _this.currentProfile.profile_id,
                            profile_name: _this.currentProfile.profile_name,
                        })
                            .then(function (response) {
                                _this.authStart = false;
                                if(response.data.data.type === 'Success') {
                                    _this.$store.dispatch('network/getAllProfiles');
                                    _this.$bvToast.toast(response.data.data.message , {
                                        title: 'Success',
                                        variant: 'success',
                                        autoHideDelay: 5000,
                                    })
                                }else {
                                    _this.$bvToast.toast(response.data.data.message , {
                                        title: 'Error',
                                        variant: 'error',
                                        autoHideDelay: 5000,
                                    })
                                }

                            })
                            .catch(function (error) {
                                _this.authStart = false;
                                _this.$bvToast.toast(`Authorized` , {
                                    title: 'Success',
                                    variant: 'success',
                                    autoHideDelay: 5000,
                                })
                            });
                    }
                }
                else {
                    console.log('User cancelled login or did not fully authorize.');
                }
            },{scope: 'pages_show_list,pages_read_user_content,pages_manage_posts'});
            return false;
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
