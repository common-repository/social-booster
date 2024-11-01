<?php
    $facebook_preview_component = apply_filters('facebook_preview_component', array(''));
?>

<div class="sb-social-media-share facebook">
    <div class="header">
       <div class="sb-from-group personal-share">
            <span class="label"><?php _e('Share on Facebook profile','rx-sb'); ?></span>
            <social-sharing :url="post.url" inline-template>
                <div>
                    <network network="facebook" class="icon">
                      <font-awesome-icon :icon="['fab', 'facebook-f']"/>
                    </network>
                </div>
            </social-sharing>
        </div>

        <ul>
            <li class="icon">
                <span><font-awesome-icon :icon="['fab', 'facebook-f']" /></span>
            </li>
            <li class="name">{{network.prof_name}}</li>
            <li class="type">{{network.auth_platform}}</li>
        </ul>
    </div>

    <form>
        <div class="sb-form-wrapper">
            <div class="input-text">
                <div class="sb-from-group pos-relative">
                    <span class="label">title</span>
                    <input type="text" placeholder="Put The Blog Title" v-model="post.title">
                    <emoji-picker @emoji="append" :search="search">
                      <div
                        class="emoji-invoker"
                        slot="emoji-invoker"
                        slot-scope="{ events: { click: clickEvent } }"
                        @click.stop="clickEvent"
                      >
                        <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                          <path d="M0 0h24v24H0z" fill="none"/>
                          <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                        </svg>
                      </div>
                      <div slot="emoji-picker" slot-scope="{ emojis, insert, display }">
                        <div class="emoji-picker" :style="{ top: display.y + 'px', left: display.x + 'px' }">
                          <div class="emoji-picker__search">
                            <input type="text" v-model="search" v-focus>
                          </div>
                          <div>
                            <div v-for="(emojiGroup, category) in emojis" :key="category">
                              <h5>{{ category }}</h5>
                              <div class="emojis">
                                <span
                                  v-for="(emoji, emojiName) in emojiGroup"
                                  :key="emojiName"
                                  @click="insert(emoji)"
                                  :title="emojiName"
                                >{{ emoji }}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </emoji-picker>
                </div>

                <div class="sb-from-group">
                    <span class="label">link</span>
                    <input type="text" placeholder="Put The Blog Link" v-model="post.url">
                    <span class="errMsg">{{linkErr}}</span>
                </div>
            </div>

            <button class="btn-default" style="margin-top:20px;" v-on:click.prevent="shareNow">
                <?php _e('Share now!','rx-sb'); ?>
                <b-spinner small label="Small Spinner" v-if="startShare"></b-spinner>
            </button>
        </div>

        <?php
         echo $facebook_preview_component[0];
        ?>

    </form>
</div>
