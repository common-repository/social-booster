<b-modal id="share-post" ref="edit-schedule" class="rex-sb-modal" size="xl" title="Edit Schedule" v-model="modalShow" @close="hideModal" hide-footer>

    <div class="post-content">
        <b-card>
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                        label="Caption"
                        label-for="caption-input"
                >
                    <b-form-textarea
                            id="caption-input"
                            v-model="scheduleData.caption"
                            rows="3"
                    />
                </b-form-group>
                <b-form-group
                        label="Schedule"
                        label-for="schedule"
                >
                    <b-form-select
                            :class="scheduleData.schedule_type"
                            id="schedule"
                            v-model="scheduleData.schedule_type"
                            :options="options"
                    />
                </b-form-group>

                <?php
                $rx_sb_edit_schedule_modal_component = apply_filters('rx_sb_edit_schedule_modal_component', array(''));
                echo $rx_sb_edit_schedule_modal_component[0];
                ?>

                <button class="btn-default" style="margin-top:20px;" @click.prevent="updateSchedule">
                    <?php _e('Update Schedule','rx-sb'); ?>
                    <b-spinner small label="Small Spinner" v-if="startUpdate"></b-spinner>
                </button>
            </form>
        </b-card>
    </div>
</b-modal>
