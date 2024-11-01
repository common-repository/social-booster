<b-modal id="share-post" ref="schedule-post" class="rex-sb-modal" size="xl" title="Schedule" v-model="modalShow" @close="hideModal" hide-footer>

    <div class="post-content calendar-add-post">
        <b-card>
            <form  ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group label="Caption" label-for="caption-input" >
                    <b-form-textarea id="caption-input" v-model="caption" rows="3" />
                </b-form-group>
                <b-form-group label="Recurring type" label-for="schedule" >
                  <b-form-select :class="schedule_type" id="schedule" v-model="schedule_type" :options="options" />
                </b-form-group>

                <b-form-group :label="'Choose media from '+currentProfile.profile_name+':'" v-if="currentProfile.networks.length" v-for="currentProfile in currentProfile" :key="currentProfile.profile_id">
                    <b-form-checkbox-group v-if="currentProfile.networks.length" v-for="network in currentProfile.networks" :key="network.id" id="media-check" class="single-checkbox" v-model="media" name="media-check">
                        <b-form-checkbox v-if="network.auth_status === 'active' && network.auth_con === 'active' " :value="network.id">
                            <span class="social-icon" :class="network.network" v-if="network.network === 'facebook'" >
                                <font-awesome-icon :icon="['fab', 'facebook-f']"/>
                            </span>
                            <span class="social-icon" :class="network.network" v-if="network.network === 'twitter'">
                                <font-awesome-icon :icon="['fab', 'twitter']"/>
                            </span>
                            <span class="social-icon" :class="network.network" v-if="network.network === 'tumblr'">
                                <font-awesome-icon :icon="['fab', 'tumblr']"/>
                            </span>
                            <span class="social-icon" :class="network.network" v-if="network.network === 'linkedin'">
                                <font-awesome-icon :icon="['fab', 'linkedin-in']"/>
                            </span>
                            <span class="social-icon" :class="network.network" v-if="network.network === 'reddit'">
                                <font-awesome-icon :icon="['fab', 'reddit-alien']"/>
                            </span>
                            <span class="social-icon" :class="network.network" v-if="network.network === 'pinterest'">
                                <font-awesome-icon :icon="['fab', 'pinterest-p']"/>
                            </span>
                            {{network.prof_name}}
                        </b-form-checkbox>
                    </b-form-checkbox-group>
                </b-form-group>

                <?php
                $rx_sb_edit_schedule_modal_component = apply_filters('rx_sb_edit_schedule_modal_component', array(''));
                echo $rx_sb_edit_schedule_modal_component[0];
                ?>
                <p v-if="errorReport" style="color:red;">{{errorReport}}</p>
                <button class="btn-default" style="margin-top:20px;" @click.prevent="addSchedule">
                    <?php _e('Add Schedule','rx-sb'); ?>
                </button>
            </form>
        </b-card>
    </div>
</b-modal>
