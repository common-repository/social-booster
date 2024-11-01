<?php
    wp_nonce_field(  basename( __FILE__ ), 'rx_sb_post_class_nonce' );
    global $wpdb;
    $post_feature_image = 'second_featured_img';
    $title =get_post_meta($post->ID,'title',true);
    $first_date =get_post_meta($post->ID,'first',true);
    $end_date =get_post_meta($post->ID,'end',true);
    $time_start =get_post_meta($post->ID,'timeStart',true);
    $time_end =get_post_meta($post->ID,'timeEnd',true);
    $value = '';
    $image = 'button"> Upload image';
    $image_size = 'thumbnail';
    $display = 'none';
    if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
        $image = '"><img src="' . $image_attributes[0] . '" style="max-width:80%;display:block;" />';
        $display = 'inline-block';
    }
    $post_permalink = get_permalink($post->ID);
    $sharer = 'https://www.facebook.com/sharer/sharer.php?u='.$post_permalink.'';
    $network_check = unserialize(SOCIAL_BOOSTER_NETWORKS);
    $network_table = $wpdb->prefix . 'sb_networks';
    $profile_table = $wpdb->prefix . 'sb_profiles';
    $network_data = $wpdb->get_results("SELECT * FROM $network_table");
    $sb_og_title = get_post_meta( $post->ID, 'sb_og_title', true );
    $sb_og_description = get_post_meta( $post->ID, 'sb_og_description', true );

?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="rx-sb-meta-tab">
    <ul class="rx-sb-meta-tabs">
        <li class="nav-item" id="instant-share"><a href="#rx-sb-instant-share">Instant Share</a></li>
        <li id="schedule-share">Schedule Share</li>
        <li class="nav-item" id="reschedule"><a href="#rx-sb-reschedule">Reschedule</a></li>
        <?php
        if($is_sb_pro_active = apply_filters('is_sb_pro_active', false)) {
            ?>
               <li class="nav-item" id="open-graph"><a href="#rx-sb-open-graph">Open Graph</a></li>
            <?php
        }
        ?>
    </ul>

    <div class="rx-sb-meta-tab-container">
        <div id="rx-sb-instant-share" class="rx-sb-meta-tab-content">
            <ul class="meta-child-tabs instant-share-child-tabs">
               <?php
                foreach ($network_check as $key => $value) {
                    ?>
                    <li><a href="#<?php echo $key; ?>-instant-share" class="<?php echo $key; ?>"><i class="fa fa-<?php echo $key; ?>"></i></a></li>
                    <?php
                }
                ?>
            </ul>

            <div class="meta-child-tab-container">
                  <?php
                  foreach ($network_check as $key => $value) {
                      ?>
                      <div id="<?php echo $key; ?>-instant-share" class="meta-child-tab-content instant-share-child-tab-content <?php echo $key; ?>-content">
                          <div class="content-wrapper">
                              <div class="instant-share-content">
                                  <div class="input-group">
                                      <label for="post-caption">Caption :</label>
                                      <textarea type="text" name="caption" id="post-caption-<?php echo $key; ?>" placeholder="Write your caption..."></textarea>
                                  </div>

                                      <?php
                                      $profiles = $wpdb->get_results("SELECT * FROM $profile_table ");
                                      foreach ($profiles as $prkey => $prval) {

                                        ?>
                                        <div class="input-group choose-profile">
                                            <label for="post-caption">Select Channel from: <?php echo $prval->profile_name; ?></label>
                                            <div class="rex-sb-checkbox">
                                            <?php
                                            if($key) {
                                                $network_data = $wpdb->get_results("SELECT * FROM $network_table WHERE network =  '$key' AND profile_id = '$prval->profile_id' ");
                                                if ($network_data) {
                                                  foreach ($network_data as $value) {
                                                      if($value->auth_status && $value->auth_con){
                                                          ?>
                                                          <div class="single-option">
                                                              <input type="checkbox" class="instant-share-channels" value="" name="profile-1" data-id="<?php echo $value->id ?>" id="instant-share-profile-<?php echo $value->id ?>">
                                                              <label for="instant-share-profile-<?php echo $value->id ?>"><span></span><?php echo $value->prof_name ?></label>
                                                          </div>
                                                          <?php
                                                        }
                                                    }
                                                }
                                                else {
                                                  ?>
                                                   <div class="single-option">
                                                      <a class="instant-share-channels" href="<?php echo admin_url().'/admin.php?page=rex-social-booster#/'; ?>">+ Connect profile</a>
                                                  </div>
                                                  <?php
                                                }
                                            }
                                            ?>
                                            </div>
                                        </div>
                                        <?php
                                      }
                                      ?>
                              </div>

                              <?php
                                  if($key == 'facebook') {
                                      $preview_body = apply_filters('facebook_preview_edit_page', array(''));
                                      echo $preview_body[0];
                                  }
                                  if($key == 'twitter') {
                                      $preview_body = apply_filters('twitter_preview_edit_page', array(''));
                                      echo $preview_body[0];
                                  }
                                  if($key == 'tumblr') {
                                      $preview_body = apply_filters('tumblr_preview_edit_page', array(''));
                                      echo $preview_body[0];
                                  }
                                  if($key == 'linkedin') {
                                      $preview_body = apply_filters('linkedin_preview_edit_page', array(''));
                                      echo $preview_body[0];
                                  }
                              ?>
                          </div>

                          <!--shcedule date time-->
                          <div class="rx-sb-share-schedule-option" data-id="<?php echo $key; ?>">
                              <?php
                              $schedule_contents = apply_filters('rx_sb_schedule_content_'.$key.'', array('
                              <div class="schedule-now">
                                  <h5 class="title">schedule now</h5>
                                  <ul>
                                      <li>
                                          <div class="form-group">
                                              <label>duration</label>
                                              <select id="rex-schedule-time-'.$key.'">
                                                <option value="none">No Recurring</option>
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                              </select>
                                          </div>
                                      </li>
                                  </ul>
                              </div>
                              '));
                              echo $schedule_contents[0];
                              ?>
                          </div>
                          <!--/shcedule date time-->
                      </div>
                      <!--/meta-child-tab-content-->

                      <?php
                  }
                  ?>
              </div>

            <button type="submit" class="share-btn share-now-btn" data-link="<?php echo $post_permalink; ?>" >share now<i class="fa fa-spinner fa-spin" id="rex-fa-spin-instant" aria-hidden="true" style="display:none;"></i></button>
            <button type="submit" class="share-btn schedule-now-btn">Schedule now<i class="fa fa-spinner fa-spin" id="rex-fa-spin-schedule" aria-hidden="true" style="display:none;"></i></button>
        </div>
        <!--/rx-sb-instant-share-->

        <div id="rx-sb-reschedule" class="rx-sb-meta-tab-content rex-sb-reschedule">
            <ul class="meta-child-tabs meta-reschedule-child-tabs">
               <?php
                foreach ($network_check as $key => $value) {
                    ?>
                    <li><a href="#<?php echo $key; ?>-reschedule" class="<?php echo $key; ?>"><i class="fa fa-<?php echo $key; ?>"></i></a></li>
                    <?php
                }
                ?>
            </ul>

            <div class="meta-child-tab-container">
                <?php
                $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';

                foreach ($network_check as $key => $value) {
                    ?>
                    <div id="<?php echo $key; ?>-reschedule" class="meta-child-tab-content meta-reschedule-child-tab-content <?php echo $key; ?>-content">
                        <div class="content-wrapper">
                            <h5 class="schedule-network-title"><?php echo $key; ?> schedule</h5>
                            <div class="rx-sb-post-meta-schedule">
                                <div class="post-meta-schedule-wrapper" id="post-meta-schedule-<?php echo $key; ?>">
                                    <ul class="network-header">
                                        <li class="profile">Profile</li>
                                        <li class="time">Schedule</li>
                                        <li class="recurring-type">Recurring</li>
                                        <li class="status">Action</li>
                                    </ul>
                                    <?php
                                        if($key) {
                                            $network_ids = $wpdb->get_results("SELECT id FROM $network_table WHERE network =  '$key' ");

                                            foreach ($network_ids as $network_id) {
                                                $schedule_data = $wpdb->get_results("SELECT *FROM $schedule_table WHERE network_id = $network_id->id ");
                                                if ($schedule_data) {

                                                    foreach ($schedule_data as $schedule) {
                                                        $shcedule_id = $schedule->id;
                                                        $shcedule_time = $schedule->schedule_time;
                                                        $data_schedule_explode = explode(' ', $shcedule_time);
                                                        $data_schedule_date = $data_schedule_explode[0];
                                                        $data_schedule_time = $data_schedule_explode[1];

                                                        $schedule_type = $schedule->schedule_type;
                                                        $network = $wpdb->get_results("SELECT network, prof_name FROM $network_table WHERE id = $schedule->network_id ");

                                                        $caption = $schedule->post_meta;
                                                        $caption_data = unserialize($caption);
                                                        $cap = $caption_data['message'];
                                                        ?>
                                                        <ul class="network-body">
                                                            <li class="profile">
                                                                <?php if($network[0]->network == 'facebook'){ ?>
                                                                    <span class="network-icon facebook"><i class="fa fa-facebook"></i></span>

                                                                <?php } elseif($network[0]->network == 'twitter'){ ?>
                                                                    <span class="network-icon twitter"><i class="fa fa-twitter"></i></span>

                                                                <?php } elseif($network[0]->network == 'tumblr'){ ?>
                                                                    <span class="network-icon tumblr"><i class="fa fa-tumblr"></i></span>

                                                                <?php } elseif($network[0]->network == 'linkedin'){ ?>
                                                                    <span class="network-icon linkedin"><i class="fa fa-linkedin"></i></span>

                                                                <?php } elseif($network[0]->network == 'pinterest'){ ?>
                                                                          <span class="network-icon pinterest"><i class="fa fa-pinterest-p"></i></span>

                                                                <?php } elseif($network[0]->network == 'reddit'){ ?>
                                                                    <span class="network-icon reddit"><i class="fa fa-reddit-alien"></i></span>
                                                                <?php } ?>

                                                                <?php echo $network[0]->prof_name; ?>
                                                            </li>
                                                            <li class="time"><?php echo str_replace(' ', ' / ', $shcedule_time); ?></li>
                                                            <li class="recurring-type"><?php echo $schedule_type; ?></li>
                                                            <li class="status">
                                                              <button class="edit edit-schedule" data-caption="<?php echo $cap; ?>" data-schedule="<?php echo $schedule_type; ?>" data-id="<?php echo $shcedule_id; ?>" data-time="<?php echo $data_schedule_time; ?>" data-date="<?php echo $data_schedule_date; ?>" title="Edit" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-pencil-square"></i></button>
                                                              <button class="delete delete-schedule" data-id="<?php echo $shcedule_id; ?>" title="Delete"><i class="fa fa-trash"></i></button>
                                                            </li>
                                                        </ul>
                                                        <?php
                                                    }

                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <!--/rx-sb-post-meta-schedule-->
                        </div>
                    </div>
                    <!--/meta-child-tab-content-->

                    <?php
                }
                ?>
            </div>
        </div>
        <!--/rx-sb-reschedule-->
        
        <?php
        if($is_sb_pro_active = apply_filters('is_sb_pro_active', false)) {
            ?>
                <div id="rx-sb-open-graph" class="rx-sb-meta-tab-content rex-sb-open-graph">
                    <div class="open-graph-content">
                        <div class="input-group">
                            <label for="og-title">Title :</label>
                            <input type="text" name="ogTitle" id="og-title" value="<?php echo $sb_og_title; ?>" placeholder="Title"/>
                        </div>
                        <div class="input-group">
                            <label for="og-descrip">Description :</label>
                            <textarea type="text" name="ogDescription" id="og-description" placeholder="Write your description..."><?php echo $sb_og_description; ?></textarea>
                        </div>
                        <div class="input-group">
                            <label></label>
                            <button class="share-btn og-submitted-data" type="submit">submit <i class="fa fa-spinner fa-spin" id="rex-og-data-spin" aria-hidden="true" style="display:none;"></i></button>
                        </div>

                    </div>
                </div>

            <?php
        }
        ?>
    </div>
</div>

<!---edit modal-->
<div class="rx-sb-meta-schedule-edit-modal" id="rx-sb-meta-schedule-edit-modal">
    <div class="modal-header">
        <h5 class="modal-title">edit schedule</h5>
        <button type="button" aria-label="Close" class="close"><i class="fa fa-times"></i></button>
    </div>

    <div class="modal-body">
        <form action="">
            <div class="form-group">
                <label for="caption-input">Caption</label>
                <textarea id="caption-input" rows="3" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="schedule">schedule</label>
                <select id="schedule">
                    <option value="none">No Recurring</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <?php
            $rx_sb_edit_schedule_modal_content = apply_filters('rx_sb_edit_schedule_modal_content', array(''));
            echo $rx_sb_edit_schedule_modal_content[0];
            ?>
            <button class="edit-submit">Update Schedule</button>
            <p id="edit-submit-success" style="color: green; display:none;"></p>
            <p id="edit-submit-fail" style="color: red; display:none;"></p>
        </form>
    </div>
</div>


<!---rx-sb-share-status-->
<div id="rx-sb-share-status">
    <span class="cross"><i class="fa fa-times"></i></span>
    <div class="rx-sb-meta-modal">
        <p></p>
    </div>
</div>
