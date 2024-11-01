<div class="sb-post-shcedule">
    <div class="schedule-wrapper">
        <h6><?php _e('Schedule reposter','rx-sb'); ?></h6>
        <form action="">
            <div class="sb-form-wrapper">
                <div class="sb-from-group">
                    <span class="label"><?php _e('Reposter','rx-sb'); ?></span>
                    <b-form-select class="reposter" v-model="reposterSelected" :options="reposters"></b-form-select>
                </div>

                <div class="sb-from-group">
                    <span class="label"><?php _e('Duration','rx-sb'); ?></span>
                    <b-form-select class="duration" v-model="durationSelected" :options="durations"></b-form-select>
                </div>

                <div class="sb-from-group dt-picker">
                    <span class="label">date</span>
                    <date-picker v-model="date" :config="dateOptions"></date-picker>
                    <font-awesome-icon :icon="['far', 'calendar-alt']" />
                </div>
                <div class="sb-from-group dt-picker">
                    <span class="label">time</span>
                    <date-picker v-model="time" :config="timeOptions"></date-picker>
                    <font-awesome-icon :icon="['far', 'clock']" />
                </div>
            </div>

            <div class="shcedule-footer">
                <div class="switcher">
                    <Switcher v-bind:switcherId="scheduleSwitcher='save_settings'" v-bind:isChecked="checked='false'"></Switcher>
                </div>
                <div class="action-button">
                    <button type="button" class="btn-default"><?php _e('Save & Continue','rx-sb'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
