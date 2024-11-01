<div class="sb-app-settings">
    <div class="header">
        <h4><img v-bind:src="getPluginDirUrl + 'admin/app/images/settings.png'" alt="icon"> Settings</h4>
    </div>

    <div class="settings-body">
        <b-card no-body>
            <b-tabs card>
              <b-tab class="single-tab" title="Post Types" active>
                    <form ref="form" @submit.stop.prevent="handleSubmit">
                        <div class="content-wrapper sb-post-type-selection">
                            <span class="title"><?php _e('Enable/Disable :','rx-sb'); ?></span>
                            <div class="title-content">
                                <b-form-checkbox-group
                                  v-model="postTypesselected"
                                  :options="postTypesOptions"
                                  switches
                                  stacked
                                ></b-form-checkbox-group>
                            </div>
                        </div>

                        <div class="content-wrapper">
                            <span class="title"></span>
                            <div class="title-content">
                                <button class="btn-default" @click.prevent="savePostTypes"> <?php _e('Save','rx-sb'); ?> </button>
                            </div>
                        </div>
                    </form>
                </b-tab>
              <b-tab class="single-tab" title="Bitly">
                <form ref="form" @submit.stop.prevent="handleSubmit">
                    <div class="content-wrapper">
                        <span class="title">bitly url shortener :</span>
                        <div class="title-content">
                            <div class="sb-settings-switcher">
                                <input class="switch-input" id="bitly-switch" v-model="bitlyEnabler" type="checkbox" checked="checked" />
                                <label for="bitly-switch"></label>
                            </div>
                        </div>
                    </div>

                    <div v-if="bitlyEnabler">
                        <div class="content-wrapper">
                            <span class="title">login :</span>
                            <div class="title-content">
                                <div class="inputs">
                                    <input v-model="login" placeholder="Login" />
                                </div>
                            </div>
                        </div>

                        <div class="content-wrapper">
                            <span class="title">API key :</span>
                            <div class="title-content">
                                <div class="inputs">
                                    <input v-model="apiKey" placeholder="Api Key" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-wrapper">
                        <span class="title"></span>
                        <div class="title-content">
                            <button class="btn-default" @click.prevent="saveBitlyData"> <?php _e('Save','rx-sb'); ?> </button>
                        </div>
                    </div>
                </form>
              </b-tab>
            </b-tabs>
        </b-card>
    </div>
</div>
