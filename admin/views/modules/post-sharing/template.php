<?php
$is_sb_pro_active = apply_filters('is_sb_pro_active', false);
?>

<div class="sb-post-sharing">
    <div class="header">
        <h4><img v-bind:src="getPluginDirUrl + 'admin/app/images/transfer.png'" alt="icon"> <?php _e('Post & Sharing','rx-sb'); ?></h4>
    </div>

    <!--recent post-->
    <div class="sb-recent-posts-tab">
        <b-card no-body>
            <b-tabs card>
                <b-tab class="latest-post" title="All blog posts" active>
                    <SearchFilter
                            :authors="authors"
                            :categories="categories"
                            @search-filter="searchFilter"
                    />
                    <RecentPosts
                            :latestPosts="allPosts"
                    />
                    <b-pagination
                            v-model="currentPage"
                            :total-rows="totalPosts"
                            :per-page="10"
                            prev-text="Previous"
                            next-text="Next"
                            align="center"
                            aria-controls="sb-recent-posts"
                            @input="paginate(currentPage)"
                            v-if="showPagination"
                            class="sb-pagination"
                            hide-goto-end-buttons
                    >
                        <span class="previous-text" slot="prev-text"><font-awesome-icon icon="angle-left" /> <?php _e('Previous','rx-sb'); ?></span>
                        <span class="next-text" slot="next-text"><?php _e('Next','rx-sb'); ?> <font-awesome-icon icon="angle-right" /></span>
                    </b-pagination>

                </b-tab>

                <b-tab class="scheduled-post" title="scheduled posts">
                  <SharedPosts
                          :sharedPosts="allSchedulePosts"
                          postType ="schedule"
                  />
                </b-tab>

                <b-tab class="shared-post" title="Shared Posts">
                    <SharedPosts
                            :sharedPosts="sharedPosts"
                            postType ="shared"
                    />
                </b-tab>

                <b-tab class="instant-sharing" title="Instant Sharing">
                    <SharedPosts
                            :sharedPosts="instantSharedPosts"
                            postType ="shared"
                    />
                </b-tab>
                <?php
                if ($is_sb_pro_active) {
                  ?>
                  <b-tab class="Share-logs" title="Share log">
                      <ChartContainer/>
                  </b-tab>
                  <?php
                }
                ?>
            </b-tabs>
        </b-card>
    </div>
    <!--/recent post-->

</div>
