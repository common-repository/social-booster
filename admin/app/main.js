import Vue from 'vue';
import App from './App.vue';
import menuFix from './utils/admin-menu-fix';
import Router from 'vue-router';
import Vuex from 'vuex';
import axios from 'axios';
import VueAxios from 'vue-axios';
// import VueSweetalert2 from 'sweetalert2/dist/sweetalert2.all.min.js'
// import VueSweetalert2 from 'vue-sweetalert2';
import VueSwal from 'vue-swal'
import { EmojiPickerPlugin } from 'vue-emoji-picker';

import { TabsPlugin, CardPlugin, ModalPlugin, ButtonPlugin, CollapsePlugin,
  FormInputPlugin, FormSelectPlugin, PaginationPlugin, TooltipPlugin, ToastPlugin,
  SpinnerPlugin, FormGroupPlugin, FormTextareaPlugin, FormCheckboxPlugin } from 'bootstrap-vue';
Vue.use(TabsPlugin);
Vue.use(CardPlugin);
Vue.use(ModalPlugin);
Vue.use(ButtonPlugin);
Vue.use(CollapsePlugin);
Vue.use(FormInputPlugin);
Vue.use(FormSelectPlugin);
Vue.use(PaginationPlugin);
Vue.use(TooltipPlugin);
Vue.use(ToastPlugin);
Vue.use(SpinnerPlugin);
Vue.use(FormGroupPlugin);
Vue.use(FormTextareaPlugin);
Vue.use(FormCheckboxPlugin);
Vue.use(EmojiPickerPlugin)

import { library } from '@fortawesome/fontawesome-svg-core'
import { faCoffee, faCheck, faUserCircle, faSearch, faImage, faSyncAlt, faPlusCircle, faStar as faStarSolid, faAngleRight, faAngleDown, faAngleLeft, faTimes, faStarHalfAlt, faPenSquare } from '@fortawesome/free-solid-svg-icons'
import { faCalendarAlt, faClock, faPaperPlane, faTrashAlt, faEdit, faStar } from '@fortawesome/free-regular-svg-icons'
import { faFacebookF, faInstagram, faTwitter, faTumblr, faLinkedinIn, faPinterestP, faRedditAlien } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faCoffee, faCheck, faUserCircle, faSearch, faPenSquare, faImage, faAngleDown, faSyncAlt, faTrashAlt, faCalendarAlt, faClock, faRedditAlien, faPlusCircle, faFacebookF, faInstagram, faTwitter, faTumblr, faStar, faStarHalfAlt, faPaperPlane, faEdit, faLinkedinIn, faAngleRight, faAngleLeft, faTimes, faStarSolid, faPinterestP)
Vue.component('font-awesome-icon', FontAwesomeIcon);


var SocialSharing = require('vue-social-sharing');
Vue.use(SocialSharing);


Vue.use(Vuex);
Vue.use(VueAxios, axios);
Vue.use(Router);
Vue.use(VueSwal);
// const options = {
//     confirmButtonColor: '#41b882',
//     cancelButtonColor: '#ff7674'
// };
// Vue.use(VueSweetalert2, options);
Vue.config.productionTip = false;
Vue.mixin({
    data: function() {
        return {
            get getPluginDirUrl() {
                return window.rx_sb_obj.plugin_dir_url;
            }
        }
    }
});


/**
 * Home Component defination
 * @type {{template: string, data: Window.rx_sb_obj.routeComponents.Home.data, mounted: Window.rx_sb_obj.routeComponents.Home.mounted}}
 */
window.rx_sb_obj.routeComponents.Home = {
    template: '#rex-sb-mainTab',
    data: function() {
        return {
            activeTab : 'Dashboard',
            currentTabComponent: 'Dashboard'
        }
    },
    mounted: function() {
        let uri = window.location.href.split('?');
        if (uri.length == 2)
        {
            let vars = uri[1].split('&');
            let getVars = {};
            let tmp = '';
            vars.forEach(function(v){
                tmp = v.split('=');
                if(tmp.length == 2)
                    getVars[tmp[0]] = tmp[1];
            });

            if(getVars.auth_success === 'twitter#/') {

                this.$bvToast.toast(`Successfully authorized and saved twitter data` , {
                    title: 'Success',
                    variant: 'success',
                    autoHideDelay: 5000,
                })
                this.activeTab = 'network';
            }else if(getVars.auth_success === 'tumblr#/') {
                this.$bvToast.toast(`Successfully authorized and saved tumblr data` , {
                    title: 'Success',
                    variant: 'success',
                    autoHideDelay: 5000,
                })
                this.activeTab = 'network';
            }else if(getVars.auth_success === 'pinterest#/') {
                this.$bvToast.toast(`Successfully authorized and saved pinterest data` , {
                    title: 'Success',
                    variant: 'success',
                    autoHideDelay: 5000,
                })
                this.activeTab = 'network';
            }else if(getVars.auth_success === 'linkedin#/') {
                this.$bvToast.toast(`Successfully authorized and saved linkedin data` , {
                    title: 'Success',
                    variant: 'success',
                    autoHideDelay: 5000,
                })
                this.activeTab = 'network';
            }else if(getVars.auth_success === 'reddit#/') {
                this.$bvToast.toast(`Successfully authorized and saved reddit data` , {
                    title: 'Success',
                    variant: 'success',
                    autoHideDelay: 5000,
                })
                this.activeTab = 'network';
            }else if(getVars.auth_success === 'failed#/') {
                this.$bvToast.toast('Failed to authorize and save data' , {
                    title: 'Error',
                    variant: 'error',
                    autoHideDelay: 5000,
                })
                this.activeTab = 'network';
            }

            if(getVars.activeTab === 'network#/') {
                this.activeTab = 'network';
            }
        }
    }
};




/**
 * Parse the route array and bind required components
 * @param routes
 */
function parseRouteComponent(routes) {
    for (let i = 0; i < routes.length; i++) {
        routes[i].component = window.rx_sb_obj.routeComponents[routes[i].component];
    }
}
parseRouteComponent(window.rx_sb_obj.routes);


const router = new Router({
    routes: window.rx_sb_obj.routes,
});

let store = new Vuex.Store({
    strict: false,
});

/* eslint-disable no-new */
new Vue({
    el: '#rx-sb-app',
    router,
    store,
    render: h => h(App),
    beforeCreate: function () {
      (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    }
});


// fix the admin menu for the slug "vue-app"
menuFix('vue-app');
