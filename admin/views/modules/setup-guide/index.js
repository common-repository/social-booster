import Vue from 'vue';
Vue.component('SetupGuide', {
    template: '#rx-sb-module-setupguide',
    data: function() {
            return {
                site_protocol:'',
                site_hostname:'',
                site_url:'',
                site_callback:'',
                copyMsg: 'Click to copy',
                currentHoverEl : ''
            }
        },
        methods: {

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
                    case 'click-copy-url-ld':
                        copyTextEl = document.querySelector('#copied-url');
                        break;
                    case 'click-copy-call-url-ld':
                        copyTextEl = document.querySelector('#copied-call-url');
                        break;
                    case 'click-copy-call-url-rd':
                        copyTextEl = document.querySelector('#copied-call-url');
                        break;
                    case 'click-copy-url-pt':
                        copyTextEl = document.querySelector('#copied-url');
                        break;
                    case 'click-copy-call-url-pt':
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
