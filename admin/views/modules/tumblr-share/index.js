import Vue from 'vue';
import instantShareModule from '../../store/modules/instant_share';
import { mapGetters } from 'vuex';
Vue.component('TumblrShare', {
    template: '#rx-sb-module-tumblr-share',
    beforeCreate: function () {
        const store = this.$store;
        if (!(store && store.state && store.state['instantShareModule'])) {
            store.registerModule('instantShareModule', instantShareModule);
        }else {
        }
    },
    props: {
        post: {
            type: Object
        },
        network: {
            type: Object
        },
    },
    data() {
        return {
            media_uploader : wp.media({
                frame:    "post",
                state:    "insert",
                multiple: false
            }),
            scheduleType: 'schedule_reposter',
            scheduleObj:  {
                'share_now' : 'Share Now',
                'schedule_reposter' : 'Schedule Reposter',
                'schedule_specific_dates' : 'Schedule Specific Dates',
            },
            linkErr: '',
            search: ''
        };
    },
    computed: {
        scheduleTypeText: function () {
            return this.scheduleObj[this.scheduleType]
        },
        ...mapGetters({
            startShare: 'instantShareModule/startShare',
        }),
    },
    methods: {
        append(emoji) {
          this.post.title += emoji
        },

        mediaUpload: function (event) {
            this.renderMediaUploader();
        },

        renderMediaUploader: function () {
            'use strict';
            var file_frame, image_url;

            if ( undefined !== file_frame ) {
                file_frame.open();
                return;
            }

            file_frame = wp.media.frames.file_frame = wp.media({
                title: "Select Featured Image",
                library: {
                    type: 'image'
                },
                button: {
                    text: "Select Image"
                },
                frame:    'post',
                state:    'insert',
                multiple: false
            });

            file_frame.on( 'insert', function() {
                let attachment = file_frame.state().get('selection').first().toJSON();
                let sizes = attachment.sizes;
                console.log(sizes);
                if( sizes.medium !== undefined  )
                    image_url = sizes.medium.url;
                else
                    image_url = attachment.url;

                document.getElementById('feature-image-tb').src = image_url;
                document.getElementById('uploadeImgLabeltb').classList.add("img-uploaded");

            });
            file_frame.open();
        },

        shareNow: function() {
            if (!this.isUrl(this.post.url)) {
                this.linkErr = "Only URL are Allowed";
                return false;
            }
          this.$store.dispatch('instantShareModule/instantShareTb',
                {
                  'post_id' : this.post.id,
                  'network_id' : this.network.network_id,
                  'id' : this.network.id,
                  'message' : this.post.title,
                  'link' : this.post.url,
                }
            ).then(r=>{
              if (r.success) {
                this.$bvToast.toast(`Successfully Shared` , {
                    title: 'Success',
                    variant: 'success',
                    autoHideDelay: 5000,
                })
              }
              else {
                this.$bvToast.toast(`Error occured` , {
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

    watch: {
        shareToastMessage(newValue, oldValue) {
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


    mounted() {
        wp.editor.initialize('wysiwyg-tb', {
            tinymce: true
        })
    },
});
