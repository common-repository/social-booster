<?php
    $dashboard_contents = apply_filters('rx_sb_dashboard_content', array('CalendarFree'));
?>


<div class="app-dashboard">
    <div class="dashboard-header">
        <h4><?php _e('Social Booster','rx-sb'); ?></h4>
        <a href="https://rextheme.com/docs/how-to-setup-social-media-accounts-with-social-booster/" target="_blank" class="btn-default">documentation</a>
    </div>

    <?php foreach ($dashboard_contents as $content): ?>
        <?php echo '<' . $content . '/>'?>
    <?php endforeach; ?>

    <!--recent post-->
    <div class="sb-recent-posts-tab">
        <b-card no-body>
            <b-tabs card>
                <b-tab class="latest-post" title="latest posts" active>
                    <RecentPosts :latestPosts="latestPosts" />
                </b-tab>

                <b-tab class="scheduled-post" title="Scheduled Posts">
                    <SharedPosts
                            :sharedPosts="allSchedulePosts"
                            postType ="schedule"
                    />
                </b-tab>
            </b-tabs>
        </b-card>
    </div>
    <!--/recent post-->

    <div class="dashboard-card-wrapper">
        <div class="single-card review">
            <h6 class="title"><?php _e('Review','rx-sb'); ?></h6>
            <div class="card-txt">
                <h2 class="point">3.5/5
                    <span>
                        <font-awesome-icon icon="star" />
                        <font-awesome-icon icon="star" />
                        <font-awesome-icon icon="star" />
                        <font-awesome-icon icon="star-half-alt" />
                        <font-awesome-icon :icon="['far', 'star']"/>
                    </span>
                </h2>
                <a href="https://wordpress.org/support/plugin/social-booster/reviews/" target="_blank" class="btn-default"><?php _e('please rate us','rx-sb'); ?></a>
            </div>
            <img v-bind:src="getPluginDirUrl + 'admin/app/images/review-icon.png'" class="card-icon" alt="review icon">
        </div>

        <div class="single-card support">
            <h6 class="title"><?php _e('Support','rx-sb'); ?></h6>
            <div class="card-txt">
                <p><?php _e('Feel free to reach out to us with any queries and get prompt response from our reliable support team.','rx-sb'); ?></p>
                <a href="https://wordpress.org/support/plugin/social-booster/" target="_blank" class="btn-default"><?php _e('Post a ticket','rx-sb'); ?></a>
            </div>
            <img v-bind:src="getPluginDirUrl + 'admin/app/images/support.png'" class="card-icon" alt="support icon">
        </div>

    </div>
</div>
