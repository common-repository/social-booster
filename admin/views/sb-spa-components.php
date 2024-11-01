<script type="text/x-template" id="rex-sb-mainTab">
  <div>
    <template>
        <div class="rex-sb-mainTab">
            <b-card no-body>
                <b-tabs card>
                    <b-tab disabled>
                        <template slot="title">
                            <b-card-img class="logo" v-bind:src="getPluginDirUrl + 'admin/app/images/logo.png'"></b-card-img>
                        </template>
                    </b-tab>
                    <?php
                      $tabs = apply_filters('rx_sb_admin_tabs', array());

                      foreach ( $tabs as $key => $tab ) {?>

                          <b-tab class="<?php echo $key; ?>" title="" <?php echo $tab['disabled'] ? 'disabled' : '' ?> :active="currentTabComponent === 'activeTab'" @click.prevent="currentTabComponent = '<?php echo $tab['component']; ?>'">
                              <template slot="title">
                                  <b-card-img class="default-icon" src="<?php echo $tab['icon']; ?>"></b-card-img>
                                  <b-card-img class="hover_icon" src="<?php echo $tab['hover_icon']; ?>"></b-card-img>
                                  <span><?php echo $tab['label']; ?></span>
                                  <?php if($tab['tooltip']): ?>
                                      <span class="coming-tooltip">coming soon</span>
                                  <?php endif; ?>
                              </template>
                              <keep-alive>
                                <component v-bind:is="currentTabComponent" v-if="currentTabComponent === '<?php echo $tab['component']; ?>'"></component>
                              </keep-alive>
                          </b-tab>
                     <?php }
                    ?>
                </b-tabs>
            </b-card>
        </div>
    </template>
  </div>
</script>
