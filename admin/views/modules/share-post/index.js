import Vue from 'vue';
import newtworkModule from '../../store/modules/networks';
import RegisterStoreModule from '@/utils/registerModule'
import { mapGetters } from 'vuex';
Vue.component('SharePost', {
    template: '#rx-sb-module-share-post',
    mixins: [ RegisterStoreModule ],
    created: function () {
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
            'hide-footer': true,
        };
    },
    mounted: function () {
        this.$store.dispatch('network/getAllProfiles');
    },
    computed: {
        ...mapGetters({
            profiles: 'network/profiles',
            currentProfile: 'network/profiles',
        }),
        modalShow: {
            get: function() {
                return this.showModal;
            },

            set: function(newValue) {
                this.$emit('hide-Modal');
            }
        }
    },
    methods: {
        hideModal(bvModalEvt) {
            bvModalEvt.preventDefault();
            this.$emit('hide-Modal');
        }
    }
});
