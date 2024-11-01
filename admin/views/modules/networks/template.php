<?php
  $networks_tabs = unserialize(SOCIAL_BOOSTER_NETWORKS);
  $is_sb_pro_active = apply_filters('is_sb_pro_active', false);
  if ($is_sb_pro_active) {
    ?>
    <div class="networks-content" >
      <div>
          <div class="network-header" >
              <ul role="tablist" class="nav nav-tabs card-header-tabs">
                  <li role="presentation" class="nav-item" v-for="(profile, i) in profiles" :key="`profile-tab-${i}`">
                      <a role="tab" data-toggle="tab" class="nav-link" v-on:click.prevent="activeProfile(i)"  :class="{active: i===activeTab }">{{ profile.profile_name }}</a>
                      <span v-on:click.prevent="deleteProfile(i)"><font-awesome-icon :icon="['far', 'trash-alt']" /></span>
                  </li>
                  <li class="nav-item">
                     <button type="button" style="display:block !important;"  v-on:click.prevent="openNewTab()" class="btn-default"><font-awesome-icon icon="plus-circle" /> add new profile</button>
                 </li>
              </ul>
          </div>
          <div class="tab-content">
            <div v-for="(profile, i) in profiles" role="tabpanel" class="tab-pane card-body" :class="{active: i===activeTab }">
              <div class="profile-name">
              <span class="label"><?php _e('Add new profile:','rx-sb'); ?></span>
              <div class="sb-input-group">
                  <input type="text" name="profile_name" placeholder="Profile Name" v-model="currentProfile.profile_name">
                  <button type="submit" v-on:click.prevent="saveProfile"><font-awesome-icon :icon="['far', 'paper-plane']" /></button>
              </div>
          </div>

              <div class="network-control-btn">
              <button type="button" class="btn-default active"><font-awesome-icon icon="plus-circle" /> {{activeNetworkText}}</button>
              <button type="button" class="btn-default" v-if="isAuthWindowShow" v-on:click.prevent='backToProfile()'>Back to profile</button>
          </div>

              <div class="network-list-wrapper" v-if="!isAuthWindowShow">
            <div class="network-lists">
                  <?php foreach ($networks_tabs as $key => $networks_tab) :?>
                      <div class="single-network">
                          <span class="network-icon <?php echo $networks_tab['name']; ?>"><font-awesome-icon :icon="['<?php echo $networks_tab['fa-prefix']; ?>', '<?php echo $networks_tab['fa-icon']; ?>']" /></span>
                          <span class="network-name"><?php echo $networks_tab['name']; ?></span>
                          <div class="network-type-wrapper">
                              <div class="network-type">
                                  <?php foreach ($networks_tab['buttons'] as $button) : ?>
                                      <button class="btn-default <?php echo $button; ?>" v-on:click.prevent='showNetworkAuth("<?php echo $networks_tab['component']; ?>", "<?php echo $networks_tab['name']; ?>", "<?php echo $button; ?>")'><?php echo $button; ?></button>
                                  <?php endforeach; ?>
                              </div>
                          </div>
                      </div>
                  <?php endforeach; ?>

              </div>

              <div class="connected-network">
                  <h6 class="title"><?php _e('Connected network','rx-sb'); ?></h6>

                  <div class="connected-network-wrapper" v-if="currentProfile.networks.length">
                      <ul class="network-header">
                          <li class="account">account</li>
                          <li class="connected">connected</li>
                          <li class="profile">profile</li>
                          <li class="time">time</li>
                          <li class="status">status</li>
                      </ul>

                      <ul class="network-body" v-for="network in currentProfile.networks">
                          <li class="account">
                            <span class="network-icon " :class="network.network" v-if="network.network === 'facebook'"><font-awesome-icon :icon="['fab', 'facebook-f']"/></span>
                              <span class="network-icon " :class="network.network" v-if="network.network === 'twitter'"><font-awesome-icon :icon="['fab', 'twitter']"/></span>
                              <span class="network-icon " :class="network.network" v-if="network.network === 'tumblr'"><font-awesome-icon :icon="['fab', 'tumblr']"/></span>
                              <span class="network-icon " :class="network.network" v-if="network.network === 'linkedin'"><font-awesome-icon :icon="['fab', 'linkedin-in']"/></span>
                              <span class="network-icon " :class="network.network" v-if="network.network === 'reddit'"><font-awesome-icon :icon="['fab', 'reddit-alien']"/></span>
                              <span class="network-icon " :class="network.network" v-if="network.network === 'pinterest'"><font-awesome-icon :icon="['fab', 'pinterest-p']"/></span>
                              <span class="network-name">{{network.network}}</span>
                          </li>
                          <li class="connected">
                              <span class="connected">
                                <font-awesome-icon v-if="network.auth_con === 'active'" icon="check" />
                                <font-awesome-icon v-if="network.auth_con === 'inactive'" icon="times" />
                              </span>
                          </li>
                          <li class="profile">{{network.prof_name}}</li>
                          <li class="time">{{network.auth_date | momentDate}}</li>
                          <li class="status">
                              <Switcher v-if="network.auth_status === 'active'" :switcherId="`switcher-${network.id}`" :dataId="network.id" :dataCheck=true></Switcher>
                              <Switcher v-if="network.auth_status === 'inactive'" :switcherId="`switcher-${network.id}`" :dataId="network.id" :dataCheck=false></Switcher>
                              <button type="button" @click.prevent="confirmDelete(network.id)">
                                  <font-awesome-icon :icon="['far', 'trash-alt']" />
                              </button>
                          </li>
                      </ul>
                  </div>
                  <p v-else><?php _e('No Network Connected.','rx-sb'); ?></p>
              </div>
              <!--/connected-network-->
          </div>
            </div>
          </div>
      </div>

      <!--/network-list-wrapper-->

      <component :is="component" v-if="component" :authPlatform="authPlatform" />
      <PageLoader v-if="spinner" />
    </div>
    <?php
  }
  else {
    ?>
    <div class="networks-content" >
<div>
    <div class="network-header" >
        <h5>{{currentProfile.profile_name}}</h5>
        <button type="button" class="btn-default" style="display:none;"><font-awesome-icon icon="plus-circle" /> add new profile</button>
    </div>

    <div class="profile-name">
        <span class="label"><?php _e('Add new profile:','rx-sb'); ?></span>
        <div class="sb-input-group">
            <input type="text" name="profile_name" placeholder="Profile Name" v-model="currentProfile.profile_name">
            <button type="submit" v-on:click.prevent="saveProfile"><font-awesome-icon :icon="['far', 'paper-plane']" /></button>
        </div>
    </div>

    <div class="network-control-btn">
        <button type="button" class="btn-default active"><font-awesome-icon icon="plus-circle" /> {{activeNetworkText}}</button>
        <button type="button" class="btn-default" v-if="isAuthWindowShow" v-on:click.prevent='backToProfile()'><?php _e('Back to profile','rx-sb'); ?></button>
    </div>

    <div class="network-list-wrapper" v-if="!isAuthWindowShow">
      <div class="network-lists">
            <?php foreach ($networks_tabs as $key => $networks_tab) :?>
                <div class="single-network">
                    <span class="network-icon <?php echo $networks_tab['name']; ?>"><font-awesome-icon :icon="['<?php echo $networks_tab['fa-prefix']; ?>', '<?php echo $networks_tab['fa-icon']; ?>']" /></span>
                    <span class="network-name"><?php echo $networks_tab['name']; ?></span>
                    <div class="network-type-wrapper">
                        <div class="network-type">
                            <?php foreach ($networks_tab['buttons'] as $button) : ?>
                                <button class="btn-default <?php echo $button; ?>" v-on:click.prevent='showNetworkAuth("<?php echo $networks_tab['component']; ?>", "<?php echo $networks_tab['name']; ?>", "<?php echo $button; ?>")'><?php echo $button; ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div class="connected-network">
            <h6 class="title"><?php _e('Connected network','rx-sb'); ?></h6>

            <div class="connected-network-wrapper" v-if="currentProfile.networks.length">
                <ul class="network-header">
                    <li class="account">account</li>
                    <li class="connected">connected</li>
                    <li class="profile">profile</li>
                    <li class="time">time</li>
                    <li class="status">status</li>
                </ul>

                <ul class="network-body" v-for="network in currentProfile.networks">
                    <li class="account">
                      <span class="network-icon " :class="network.network" v-if="network.network === 'facebook'"><font-awesome-icon :icon="['fab', 'facebook-f']"/></span>
                        <span class="network-icon " :class="network.network" v-if="network.network === 'twitter'"><font-awesome-icon :icon="['fab', 'twitter']"/></span>
                        <span class="network-icon " :class="network.network" v-if="network.network === 'tumblr'"><font-awesome-icon :icon="['fab', 'tumblr']"/></span>
                        <span class="network-icon " :class="network.network" v-if="network.network === 'linkedin'"><font-awesome-icon :icon="['fab', 'linkedin-in']"/></span>
                        <span class="network-icon " :class="network.network" v-if="network.network === 'reddit'"><font-awesome-icon :icon="['fab', 'reddit-alien']"/></span>
                        <span class="network-icon " :class="network.network" v-if="network.network === 'pinterest'"><font-awesome-icon :icon="['fab', 'pinterest-p']"/></span>
                        <span class="network-name">{{network.network}}</span>
                    </li>
                    <li class="connected">
                        <span class="connected">
                          <font-awesome-icon v-if="network.auth_con === 'active'" icon="check" />
                          <font-awesome-icon v-if="network.auth_con === 'inactive'" icon="times" />
                        </span>
                    </li>
                    <li class="profile">{{network.prof_name}}</li>
                    <li class="time">{{network.auth_date | momentDate}}</li>
                    <li class="status">
                        <Switcher v-if="network.auth_status === 'active'" :switcherId="`switcher-${network.id}`" :dataId="network.id" :dataCheck=true></Switcher>
                        <Switcher v-if="network.auth_status === 'inactive'" :switcherId="`switcher-${network.id}`" :dataId="network.id" :dataCheck=false></Switcher>
                        <button type="button" @click.prevent="confirmDelete(network.id)">
                            <font-awesome-icon :icon="['far', 'trash-alt']" />
                        </button>
                    </li>
                </ul>
            </div>
            <p v-else><?php _e('No Network Connected.','rx-sb'); ?></p>
        </div>
        <!--/connected-network-->
    </div>
</div>

<!--/network-list-wrapper-->

<component :is="component" v-if="component" :authPlatform="authPlatform" />
</div>


    <?php
  }
?>
