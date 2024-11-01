<div class="sb-switcher">
    <input class="switch-input" v-bind:id="switcherId" v-bind:dataid="dataId" type="checkbox" v-bind:checked="dataCheck"  v-on:change="statusOnchange($event.target)" />
    <label v-bind:for="switcherId"></label>
    <span class="title"><?php _e('Apply settings to all network','rx-sb'); ?></span>
</div>
