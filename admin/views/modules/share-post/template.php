<b-modal id="share-post" ref="share-post" class="rex-sb-modal" size="xl" title="share post" v-model="modalShow" @close="hideModal" hide-footer>

    <div class="post-content">
        <span class="select-text">
            <img v-bind:src="getPluginDirUrl + 'admin/app/images/arrow.png'" alt="arrow">
            <font-awesome-icon icon="user-circle" /> <?php _e('Select a profile','rx-sb'); ?>
        </span>
        <b-card no-body>
            <b-tabs card>

                <b-tab class="single-tab" title=""  v-for="currentProfile in currentProfile" :key="currentProfile.profile_id" active>
                    <template slot="title">
                        <span>{{currentProfile.profile_name}}</span>
                    </template>
                    <div class="connected-network-wrapper" v-if="currentProfile.networks.length">
                        <b-card no-body>
                            <b-tabs card>
                                <b-tab class="child-card-body" title="" v-for="(network, id) in currentProfile.networks" :key="id" :disabled="network.auth_status === 'inactive' || network.auth_con === 'inactive' ">
                                    <template slot="title">
                                        <span class="tab-icon " :class="network.network" v-if="network.network === 'facebook'" ><font-awesome-icon :icon="['fab', 'facebook-f']"/></span>
                                        <span class="tab-icon " :class="network.network" v-if="network.network === 'twitter'"><font-awesome-icon :icon="['fab', 'twitter']"/></span>
                                        <span class="tab-icon " :class="network.network" v-if="network.network === 'tumblr'"><font-awesome-icon :icon="['fab', 'tumblr']"/></span>
                                        <span class="tab-icon " :class="network.network" v-if="network.network === 'linkedin'"><font-awesome-icon :icon="['fab', 'linkedin-in']"/></span>
                                        <span class="tab-icon " :class="network.network" v-if="network.network === 'reddit'"><font-awesome-icon :icon="['fab', 'reddit-alien']"/></span>
                                        <span class="tab-icon " :class="network.network" v-if="network.network === 'pinterest'"><font-awesome-icon :icon="['fab', 'pinterest-p']"/></span>
                                        <span class="network-name">{{network.auth_platform}}</span>
                                        <span class="coming-tooltip" v-if="network.auth_status === 'inactive'"><?php _e('Network inactive','rx-sb'); ?></span>
                                    </template>
                                   <component :is="network.component" :post="post" :network="network"/>
                                </b-tab>
                            </b-tabs>
                        </b-card>
                    </div>
                    <p v-else><?php _e('No Network Connected','rx-sb'); ?></p>
                </b-tab>
            </b-tabs>
        </b-card>
    </div>
</b-modal>
